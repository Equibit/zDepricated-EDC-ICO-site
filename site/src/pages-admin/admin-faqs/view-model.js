import can from 'can';
import 'can/map/define/';
import AdminFAQs from 'easyapp/models/admin-faqs/';
import templateAdminFAQsNew from './template-admin-faqs-new.stache!';
import templateAdminFAQsEdit from './template-admin-faqs-edit.stache!';

export default can.Map.extend({
  templateAdminFAQsNew: templateAdminFAQsNew,
  templateAdminFAQsEdit: templateAdminFAQsEdit,
  define: {
    loadedData: {
      value: false,
      serialize: false
    },
    filterLang: {
      value: 'en',
      serialize: false
    },
    adminFAQsData: {
      value: [],
      set(newValue) {
        this.filterByLang('en', newValue);
        return newValue;
      },
      serialize: false
    },
    adminFAQs: {
      value: [],
      serialize: false
    },
    newFAQ: {
      value: new AdminFAQs({}),
      Type: AdminFAQs
    },
    editFAQ: {
      value: null,
      Type: AdminFAQs
    },
    loadedFAQ: {
      value: null,
      Type: AdminFAQs,
      serialize: false
    },
  },
  filterByLang(lang, newValue) {
    if (typeof newValue === 'undefined') newValue = this.attr("adminFAQsData");
    if (newValue.length > 0) var newData = newValue.filter((elem, index, arr) => elem.lang == lang && !elem.deleting);
    this.attr("adminFAQs", newData);
  },
  addFAQ(faq) {
    this.attr("buttonRunning", 'disabled');
    this.attr("inputDisabled", 'disabled');
    this.attr("successMessage", false);
    this.attr("generalError", false);
    this.attr("missingInformation", false);

    if (faq.attr("question").length > 0 && faq.attr("answer").length > 0) {
      faq.save(saved => {
        this.attr("buttonRunning", null);
        this.attr("inputDisabled", null);
        this.attr("successMessage", true);
        this.attr("generalError", false);
        this.attr("missingInformation", false);
        this.attr('adminFAQsData').push(saved);
        this.filterByLang(this.attr("filterLang"));
        this.attr('newFAQ', {});
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
  delFAQ(faq) {
    faq.attr('deleting', true);
    faq.destroy();
    return false;
  },
  editThisFAQ(faq) {
    this.attr("editFAQ", faq);
    $('#admin-edit-faq-modal').modal('show');
  },
  saveFAQ(faq) {
    this.attr("buttonRunning", 'disabled');
    this.attr("inputDisabled", 'disabled');
    this.attr("successMessage", false);
    this.attr("generalError", false);
    this.attr("missingInformation", false);

    if (faq.attr("question").length > 0 && faq.attr("answer").length > 0) {
      faq.save(() => {
        this.attr("buttonRunning", null);
        this.attr("inputDisabled", null);
        this.attr("successMessage", true);
        this.attr("generalError", false);
        this.attr("missingInformation", false);
        $('#admin-edit-faq-modal').modal('hide');
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
  }
});