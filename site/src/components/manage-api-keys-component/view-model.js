import can from 'can';
import 'can/map/define/';
import APIKeysModel from 'easyapp/models/api-keys/';
import templateAddKey from './template-add-key.stache!';
import templateShowSecret from './template-show-secret.stache!';

export default can.Map.extend({
  templateAddKey: templateAddKey,
  templateShowSecret: templateShowSecret,
  define: {
    myKeys: {
      value: []
    },
    newKey: {
      value: new APIKeysModel({}),
      Type: APIKeysModel
    },
    loadedKey: {
      value: null,
      Type: APIKeysModel,
      serialize: false
    }
  },
  clearKey(){
    this.attr('newKey', {});
  },
  openAddKey() {
    this.clearKey();
    $('#manage-api-keys-modal').modal('hide');
    setTimeout(() => $('#add-key-modal').modal('show'), 500);
  },
  cancelAddKey() {
    $('#add-key-modal').modal('hide');
    setTimeout(() => $('#manage-api-keys-modal').modal('show'), 500);
  },
  addKey(key) {
    key.attr("buttonRunning", true);
    key.save(savedKey => {
      this.attr('myKeys').push(savedKey);
      $('#add-key-modal').modal('hide');
      setTimeout(() => $('#manage-api-keys-modal').modal('show'), 500);
      key.attr("buttonRunning", false);
    });
  },
  deleteKey(key) {
    key.attr('deleting', true);
    key.destroy();
  },
  showKey(key) {
    $('#manage-api-keys-modal').modal('hide');
    this.attr("loadedKey", key);
    setTimeout(() => $('#show-secret-modal').modal('show'), 500);
  },
  closeShowKey() {
    $('#show-secret-modal').modal('hide');
    setTimeout(() => $('#manage-api-keys-modal').modal('show'), 500);
  },
});