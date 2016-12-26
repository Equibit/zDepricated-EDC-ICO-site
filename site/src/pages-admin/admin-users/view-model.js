import can from 'can';
import 'can/map/define/';
import AdminUsers from 'easyapp/models/admin-users/';
import AdminNotify from 'easyapp/models/admin-notify/';
import templateAdminUsersOptions from './template-admin-users-options.stache!';
import templateAdminUsersNotify from './template-admin-users-notify.stache!';

export default can.Map.extend({
  templateAdminUsersOptions: templateAdminUsersOptions,
  templateAdminUsersNotify: templateAdminUsersNotify,
  define: {
    loadedData: {
      value: false,
      serialize: false
    },
    search: {
      type: 'string',
      serialize: false
    },
    users: {
      value: [],
      serialize: false
    },
    newNotify: {
      value: new AdminNotify({}),
      Type: AdminNotify
    },
    loadedNotifications: {
      value: [],
      serialize: false
    },
    usersData: {
      value: [],
      set(newValue) {
        this.attr("users", newValue);
        return newValue;
      },
      serialize: false
    },
    loadedUser: {
      value: null,
      Type: AdminUsers,
      serialize: false
    },
  },
  updateSearch(searchStr) {
    var newData = this.attr("usersData").filter((elem, index, arr) =>  elem.userName.includes(searchStr) || elem.email.includes(searchStr));
    this.attr("users", newData);
  },
  openUserOptions(adminUser) {
    this.attr('loadedUser', adminUser);
    $('#admin-users-option-modal').modal('show');
  },
  saveOptions(userOptions) {
    userOptions.save(() => {
      $('#admin-users-option-modal').modal('hide');
    });
  },
  openUserNotify(userID) {
    this.attr("newNotify.userID", userID);
    $('#admin-users-notify-modal').modal('show');
  },
  sendNotify(notify) {
    this.attr("buttonRunning", 'disabled');
    this.attr("inputDisabled", 'disabled');
    this.attr("successMessage", false);
    this.attr("generalError", false);
    this.attr("missingInformation", false);

    if (notify.attr("title").length > 0 && notify.attr("text").length > 0) {
      notify.save(() => {
        this.attr("buttonRunning", null);
        this.attr("inputDisabled", null);
        this.attr("successMessage", true);
        this.attr("generalError", false);
        this.attr("missingInformation", false);
        notify.attr('id', null);
        notify.attr('title', null);
        notify.attr('text', null);
      }, () => {
        this.attr("buttonRunning", null);
        this.attr("inputDisabled", null);
        this.attr("generalError", true);
        this.attr("successMessage", false);
      });
    } else {
      this.attr("buttonRunning", null);
      this.attr("inputDisabled", null);
      this.attr("missingInformation", true);
    }

    return false;
  },
  deleteUser(user) {
    $('#admin-users-option-modal').modal('hide');
    user.attr("deleting", true);
    user.destroy();
    return false;
  }
});