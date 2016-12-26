import can from 'can';
import 'can/map/define/';
import AdminCommTemplates from 'easyapp/models/admin-comm-templates/';
import templateAdminCommView from './template-admin-comm-view.stache!';
import templateAdminCommEdit from './template-admin-comm-edit.stache!';
import templateAdminCommNew from './template-admin-comm-new.stache!';

export default can.Map.extend({
  templateAdminCommView: templateAdminCommView,
  templateAdminCommEdit: templateAdminCommEdit,
  templateAdminCommNew: templateAdminCommNew,
  define: {
    loadedData: {
      value: false,
      serialize: false
    },
    adminCommTemplatesData: {
      value: [],
      serialize: false
    },
    loadedComm: {
      value: null,
      Type: AdminCommTemplates
    },
    newComm: {
      value: new AdminCommTemplates({}),
      Type: AdminCommTemplates
    }
  },
  viewComm(comm) {
    this.attr("loadedComm", comm);
    $('#admin-comm-view-modal').modal('show');
    var shadow = document.querySelector('#htmlTemplate').createShadowRoot();
    shadow.innerHTML = comm.attr("htmlTemplate");
  },
  editComm(comm) {
    this.attr("loadedComm", comm);
    $('#admin-comm-edit-modal').modal('show');
  },
  newCommTemplate() {
    this.attr("newComm", {});
    $('#admin-comm-new-modal').modal('show');
  },
  copyComm(comm) {
    this.attr("newComm.subject", comm.attr("subject"));
    this.attr("newComm.htmlTemplate", comm.attr("htmlTemplate"));
    this.attr("newComm.textTemplate", comm.attr("textTemplate"));
    this.attr("newComm.smsTemplate", comm.attr("smsTemplate"));
    $('#admin-comm-new-modal').modal('show');
  },
  saveComm(comm) {
    comm.attr("buttonRunning", true);
    comm.attr("inputDisabled", true);
    comm.save(() => {
      $('#admin-comm-edit-modal').modal('hide');
      comm.attr("buttonRunning", false);
      comm.attr("inputDisabled", false);
    });
  },
  saveNewComm(comm) {
    comm.attr("buttonRunning", true);
    comm.attr("inputDisabled", true);
    comm.save(data => {
      this.attr("adminCommTemplatesData").push(data);
      $('#admin-comm-new-modal').modal('hide');
      comm.attr("buttonRunning", false);
      comm.attr("inputDisabled", false);
      this.attr("newComm", {});
    });
  },
  deleteComm(comm) {
    comm.destroy();
  }
});