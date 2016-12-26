import can from 'can';
import 'can/map/define/';
import AdminBlog from 'easyapp/models/admin-blog/';
import templateAdminBlogNew from './template-admin-blog-new.stache!';
import templateAdminBlogEdit from './template-admin-blog-edit.stache!';

export default can.Map.extend({
  templateAdminBlogNew: templateAdminBlogNew,
  templateAdminBlogEdit: templateAdminBlogEdit,
  define: {
    loadedData: {
      value: false,
      serialize: false
    },
    filterLang: {
      value: 'en',
      serialize: false
    },
    adminBlogData: {
      value: [],
      set(newValue) {
        this.filterByLang('en', newValue);
        return newValue;
      },
      serialize: false
    },
    adminBlog: {
      value: [],
      serialize: false
    },
    newBlog: {
      value: new AdminBlog({}),
      Type: AdminBlog
    },
    editBlog: {
      value: null,
      Type: AdminBlog
    },
    loadedBlog: {
      value: null,
      Type: AdminBlog,
      serialize: false
    },
  },
  filterByLang(lang, newValue) {
    if (typeof newValue === 'undefined') newValue = this.attr("adminBlogData");
    if (newValue.length > 0) var newData = newValue.filter((elem, index, arr) => elem.lang == lang && !elem.deleting);
    this.attr("adminBlog", newData);
  },
  addBlog(blog) {
    this.attr("buttonRunning", 'disabled');
    this.attr("inputDisabled", 'disabled');
    this.attr("successMessage", false);
    this.attr("generalError", false);
    this.attr("missingInformation", false);

    if (blog.attr("title").length > 0 && blog.attr("short").length > 0) {
      blog.save(saved => {
        this.attr("buttonRunning", null);
        this.attr("inputDisabled", null);
        this.attr("successMessage", true);
        this.attr("generalError", false);
        this.attr("missingInformation", false);
        this.attr('adminBlogData').push(saved);
        this.filterByLang(this.attr("filterLang"));
        this.attr('newBlog', {});
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
  delBlog(blog) {
    blog.attr('deleting', true);
    blog.destroy();
    return false;
  },
  editThisBlog(blog) {
    this.attr("editBlog", blog);
    $('#admin-edit-blog-modal').modal('show');
  },
  saveBlog(blog) {
    this.attr("buttonRunning", 'disabled');
    this.attr("inputDisabled", 'disabled');
    this.attr("successMessage", false);
    this.attr("generalError", false);
    this.attr("missingInformation", false);

    if (blog.attr("question").length > 0 && blog.attr("answer").length > 0) {
      blog.save(() => {
        this.attr("buttonRunning", null);
        this.attr("inputDisabled", null);
        this.attr("successMessage", true);
        this.attr("generalError", false);
        this.attr("missingInformation", false);
        $('#admin-edit-blog-modal').modal('hide');
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