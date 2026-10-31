const {test} = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');

// Exercise the actual Alpine component, with only the Livewire transport stubbed.
const source = fs.readFileSync(path.join(__dirname, '../../resources/views/forms/components/layup-builder.blade.php'), 'utf8').match(/<script>([\s\S]*?)<\/script>/)[1];
function fixture(content) {
    let factory;
    const pending = [];
    const listeners = {};
    const wire = {callSchemaComponentMethod(key, method, args) {
        return new Promise(resolve => pending.push({method, args, resolve}));
    }};
    vm.runInNewContext(source, {
        Alpine: {data(name, callback) {factory = callback;}}, $wire: wire,
        Livewire: {hook() {}}, window: {addEventListener(name, callback) {listeners[name] = callback;}},
        console, setTimeout,
    });
    const builder = factory({content: JSON.parse(JSON.stringify(content)), widgetRegistry: [], breakpoints: {}, translations: {}, initialLivePreviews: {}});
    builder.init();
    return {builder, pending, listeners};
}
const content = () => ({sections: [
    {id: 's1', rows: [{id: 'r1', columns: []}]}, {id: 's2', rows: []},
]});

test('late row-add response stays in the originating section', async () => {
    const {builder, pending} = fixture(content());
    builder.rowAddAt([12], 1);
    assert.equal(pending[0].args.sectionId, 's1');
    builder.activeSectionId = 's2';
    pending[0].resolve({id: 'r2', order: 1, columns: []});
    await Promise.resolve();
    assert.equal(builder.content.sections[0].rows.length, 2);
    assert.equal(builder.content.sections[1].rows.length, 0);
});

test('late row-duplicate response stays in the originating section', async () => {
    const {builder, pending} = fixture(content());
    builder.rowDuplicate('r1');
    builder.activeSectionId = 's2';
    pending[0].resolve({id: 'r2', order: 1, columns: []});
    await Promise.resolve();
    assert.equal(builder.content.sections[0].rows.length, 2);
    assert.equal(builder.content.sections[1].rows.length, 0);
});

test('undo and redo restore exactly one step and preserve both sections', () => {
    const {builder} = fixture(content());
    builder.activeSectionId = 's2';
    builder.rows = [{id: 'r2', columns: []}];
    builder.pushHistory();
    builder.undo();
    assert.equal(builder.content.sections[1].rows.length, 0);
    builder.redo();
    assert.equal(builder.content.sections[1].rows.length, 1);
    assert.equal(builder.historyIndex, builder.history.length - 1);
});

test('widget updates reach an unselected section', () => {
    const data = content();
    data.sections[0].rows[0].columns = [{id: 'c1', widgets: [{id: 'w1', type: 'text', data: {content: 'Before'}}]}];
    const {builder, listeners} = fixture(data);
    builder.activeSectionId = 's2';
    listeners['layup-widget-updated']({detail: {rowId: 'r1', columnId: 'c1', widgetId: 'w1', data: {content: 'After'}}});
    assert.equal(builder.allRows[0].columns[0].widgets[0].data.content, 'After');
});

test('typing undo remains native to inputs', () => {
    const {builder, pending} = fixture(content());
    let prevented = false;
    builder.onKeyDown({ctrlKey: true, key: 'z', target: {closest() {return true;}}, preventDefault() {prevented = true;}});
    assert.equal(prevented, false);
    assert.equal(pending.length, 0);
});
