define({
  // -------------------------------------------[miscellaneous]---------------------------------------------------------

  // ----------[system] variables
  locale: 'en',
  timestamp: 'MMM D, YYYY',
  timestampDetailed: 'MMM Do YYYY, h:mm:ss a',

  // ----------[navigation] extras
  navSelectLanguage: "Select Language",
  navUserMenu: "User Menu",
  navAdminMenu: "Admin Menu",
  navLogout: "Logout",

  // ----------[messages]
  noMessages: "No Messages",

  // ----------[alert] messages
  warning: "Warning",
  error: "Error",
  sessionExpired: "Your Session Expired, Please Login Again!",

  // ----------[password] strength
  longerPasswordNeeded: "Longer password recommended!",
  strongPassword: "This password is strong!",
  okPassword: "This password is just OK!",
  weakPassword: "This password is weak!",

  // -------------------------------------------[database]--------------------------------------------------------------

  // ----------[database] factors
  i18nNone: "None",
  i18nNoneText: "No Second Factor",
  i18nEmail: "Email",
  i18nEmailText: "Send Email Pin",
  i18nSMS: "SMS",
  i18nSMSText: "Text Message Pin",

  // ----------[database] roles
  i18nAdmin: "Admin",
  i18nAdminText: "System Management",
  i18nUser: "User",
  i18nUserText: "General User",
  i18nManage: "Manage",
  i18nManageText: "Manage Users",

  // -------------------------------------------[admin page]------------------------------------------------------------

  // ----------[page] admin comm templates
  adminCommTemplatesNav: "Communication Templates",
  adminCommTemplatesTitle: "Communication Templates",
  adminCommTemplatesTemplateName: "Template Name",
  adminCommTemplatesHasSubject: "Has Subject",
  adminCommTemplatesHasHTML: "Has HTML",
  adminCommTemplatesHasText: "Has Text",
  adminCommTemplatesHasSMS: "Has SMS",
  adminCommTemplatesHasKeys: "Keys Found",
  adminCommTemplatesActions: "Actions",
  adminCommTemplatesNone: "No Communication Templates",
  adminCommTemplatesCreateBtn: "Create New Template",
  // [sub-page] new / view / edit comm templates
  adminCommTemplatesViewTitle: "Viewing Template",
  adminCommTemplatesNewTitle: "Create New Template",
  adminCommTemplatesCloseBtn: "Close",
  adminCommTemplatesSaveBtn: "Save",
  adminCommTemplatesEditTitle: "Edit Template",

  // ----------[page] admin users
  adminUserNav: "User Accounts",
  adminUserTitle: "User Accounts",
  adminUsersSearch: "Search",
  adminUsersUsername: "Username",
  adminUsersEmailConfirmed: "Confirmed",
  adminUsersEmail: "Email",
  adminUsersPhoneConfirmed: "Confirmed",
  adminUsersPhone: "Phone",
  adminUsersLock: "Locked",
  adminUsersLanguage: "Language",
  adminUsersLocked: "Locked",
  adminUsersNotLocked: "Not Locked",
  adminUsersCreated: "Created",
  adminUsersOptions: "Options",
  adminUsersNotify: "Notify",
  adminUsersUserOptions: "User Options",
  adminUsersUserNotify: "Notify",
  adminUsersNoUsers: "No Users Match Search",
  // [sub-page] users options
  userOptionsTitle: "User Options for",
  userOptionsLock: "Account Locked",
  userOptionsNotifications: "Email Notifications",
  userOptionsEmailConfirmed: "Email Confirmed",
  userOptionsPhoneConfirmed: "Phone Confirmed",
  userOptionsDeleteBtn: "Delete User",
  userOptionsSaveBtn: "Save Changes",
  // [sub-page] users options
  userNotifyTitle: "User Notifications",
  userNotifySubject: "Notification Subject",
  userNotifyMessage: "Notification Message",
  userNotifySent: "Notification Sent.",
  userNotifyUnknownError: "Unknown Error, please try again.",
  userNotifyMissingInformation: "Missing information to send.",
  userNotifySend: "Send Notification",

  // ----------[page] admin text variables
  adminTextNav: "Text Variables",
  adminTextTitle: "Edit Email and SMS Text Variables",
  adminTextEmailMe: "Test The Following Email",
  adminTextSMSMe: "Test The Following SMS",
  adminTextVariables: "Text Variables",
  adminTextKey: "Variable Key",
  adminTextLang: "Language",
  adminTextFound: "Key Used",
  adminTextActions: "Actions",
  adminTextCreateBtn: "Create New Variable",

  // ----------[page] admin faqs
  adminFAQsNav: "Edit FAQs",
  adminFAQsTitle: "Edit FAQs",
  adminFAQsAddNew: "Add New FAQ",
  adminFAQsEdit: "Edit",
  adminFAQsDelete: "Delete",
  // [sub-page] new faq
  newFAQTitle: "Add New FAQ",
  newFAQQuestion: "New Question",
  newFAQAnswer: "New Answer",
  newFAQLanguage: "Select Display Language",
  newFAQSaved: "New FAQ has been Saved!",
  newFAQUnknownError: "Unknown Error, please try again.",
  newFAQMissingInformation: "New question or answer cannot be empty.",
  newFAQSave: "Save New FAQ",
  // [sub-page] edit faq
  editFAQTitle: "Edit FAQ",
  editFAQQuestion: "Edit Question",
  editFAQAnswer: "Edit Answer",
  editFAQSaved: "FAQ has been Saved!",
  editFAQUnknownError: "Unknown Error, please try again.",
  editFAQMissingInformation: "Question or answer cannot be empty.",
  editFAQSave: "Save FAQ",

  // ----------[page] admin blog
  adminBlogNav: "Edit Blog",
  adminBlogTitle: "Edit Blog",
  adminBlogAddNew: "Add New Blog",
  adminBlogEdit: "Edit",
  adminBlogDelete: "Delete",
  // [sub-page] new faq
  newBlogTitle: "Add New Blog",
  newBlogBlogTitle: "Post Title",
  newBlogImgLink: "URL to Post Image",
  newBlogShortDesc: "Short Post Description",
  newBlogPost: "Full Blog Post",
  newBlogLink: "Or Link to Post",
  newBlogLanguage: "Select Display Language",
  newBlogSaved: "New blog post has been Saved!",
  newBlogUnknownError: "Unknown Error, please try again.",
  newBlogMissingInformation: "New post title or short description cannot be empty.",
  newBlogSave: "Save New Blog",
  // [sub-page] edit faq
  editBlogTitle: "Edit Blog",
  editBlogBlogTitle: "Post Title",
  editBlogImgLink: "URL to Post Image",
  editBlogShortDesc: "Short Post Description",
  editBlogPost: "Full Blog Post",
  editBlogLink: "Or Link to Post",
  editBlogSaved: "Blog has been Saved!",
  editBlogUnknownError: "Unknown Error, please try again.",
  editBlogMissingInformation: "Post title or short description cannot be empty.",
  editBlogSave: "Save Blog",

  // -------------------------------------------[pages]-----------------------------------------------------------------

  // ----------[page] blog
  pageBlogNav: "Our Blog",
  pageBlogTitle: "Our Blog",
  pageBlogReadMore: "Read Post",

  // ----------[page] faqs
  pageFAQsNav: "FAQs",
  pageFAQsTitle: "FAQs",
  pageFAQsSearch: "Search",

  // -------------------------------------------[component]-------------------------------------------------------------

  // ----------[component] notification
  notificationComponentNoMessages: "No Messages",
  notificationComponentDelete: "Del",

  // ----------[component] manage api keys
  manageKeysNav: "Manage API Keys",
  manageKeysTitle: "Manage API Keys",
  manageKeysNoKeys: "You currently have no API keys!",
  manageKeysAddKeyBtn: "Generate New Key",
  manageKeysCancelBtn: "Close",

  addKeyTitle: "Generate Key",
  addKeyDescription: "New Key Description",
  addKeyCancelBtn: "Cancel",
  addKeyCreateBtn: "Generate New Key",

  showKeyTitle: "Show Secret Key",
  showKeyCloseBtn: "Close",

  // ----------[component] chat
  chatComponentNew: "New",
  chatComponentEnterSend: "Hit Enter to Send...",
  chatComponentJoin: "has joined the chat",
  chatComponentLeft: "has left the chat",

  // ----------[component] change phone
  changePhoneNav: "Add/Change Phone Number",
  changePhoneTitle: "Add/Change Phone Number",
  changePhoneNew: "New Mobile Phone Number",
  changePhoneAlreadyUsedError: "This phone number is already used.",
  changePhoneInvalidError: "This phone number doesn't appear valid.",
  changePhoneSecondFactor: "Sent Pin",
  changePhoneSecondFactorSent: "We sent you a pin, please enter it here and submit the form again.",
  changePhoneSuccess: "Phone Number has Been Changed.",
  changePhonePleaseWait: "Please wait and do not close this. We are sending you a pin that will need to be entered in this form.",
  changePhoneBtn: "Add/Change Phone Number",

  // ----------[component] change email
  changeEmailNav: "Change Email",
  changeEmailTitle: "Change Account Email",
  changeEmailNew: "New Email Address",
  changeEmailAlreadyUsedError: "This email is already used.",
  changeEmailInvalidError: "This email doesn't appear valid.",
  changeEmailSecondFactor: "Sent Pin",
  changeEmailSecondFactorSent: "We sent you a pin, please enter it here and submit the form again.",
  changeEmailSuccess: "Email has Been Changed.",
  changeEmailPleaseWait: "Please wait and do not close this. We are sending you a pin that will need to be entered in this form.",
  changeEmailBtn: "Change Account Email",

  // ----------[component] change password
  changePasswordNav: "Change Password",
  changePasswordTitle: "Change Password",
  changePasswordCurrent: "Current Password",
  changePasswordNew: "New Password",
  changePasswordRetype: "Retype New Password",
  changePasswordMismatchError: "Passwords do not match.",
  changePasswordError: "Current password error",
  changePasswordSuccess: "Password has been changed",
  changePasswordBtn: "Change Password",

  // ----------[component] change question some of following is also used in the sign up component
  changeQuestionNav: "Change Security Question",
  changeQuestionTitle: "Change Security Question",
  changeQuestionSelect: "Select New Security Question",
  // below used in sign up component and forgot password view model
  changeQuestionQuestions: [
    "What is the first name of the person you first kissed?",
    "What is the name of the place your wedding reception was held?",
    "What is the name of your favorite childhood friend?",
    "In what city or town did your mother and father meet?",
    "What was your favorite sport in high school?"],
  changeQuestionError: "You must select a new question",
  changeQuestionAnswer: "Change Security Answer",
  changeQuestionLengthError: "Security Answer must be at least 6 characters long!",
  changeQuestionUnknownError: "Unknown Error, please try again.",
  changeQuestionSuccess: "Your new security question and answer saved!",
  changeQuestionBtn: "Save New Questions and Answer",

  // ----------[component] forgot password
  forgotPasswordTitle: "Forgot Password",
  forgotPasswordUsernameOrEmail: "Username or Email",
  forgotPasswordSecondFactor: "Sent Pin",
  forgotPasswordNew: "New Password",
  forgotPasswordRetype: "Retype New Password",
  forgotPasswordMismatchError: "Passwords do not match.",
  forgetPasswordError: "Unknown Error, please try again.",
  forgetPasswordSuccess: "Password Changed.",
  forgotPasswordPleaseWait: "Please wait and do not close this. We are sending you a pin that will need to be entered in this form.",
  forgotPasswordBtn: "Reset Password",

  // ----------[component] login
  loginNav: "Login",
  loginTitle: "Login",
  loginUsername: "Username",
  loginStay: "Stay Logged In",
  loginPassword: "Password",
  loginForgotPasswordLink: "Forgot Password?",
  loginSecondFactor: "Sent Pin",
  loginGeneralError: "Login Error, try again!",
  loginBtn: "Login",

  // ----------[component] manage child users
  manageUsersNav: "Manage Users",
  manageUsersTitle: "Manage Users",
  manageUsersNoUsers: "No Users to Manage",
  manageUsersUsername: "Username",
  manageUsersSecondFactorType: "2nd Factor Type",
  manageUsersRoles: "User Roles",
  manageUsersActions: "Actions",
  manageUsersNoFactorSelected: "Nothing Selected",
  manageUsersNoRolesSelected: "No Role Selected",
  manageUsersDeleteBtn: "Delete",
  manageUsersNewPasswordBtn: "New Password",
  manageUsersSaveBtn: "Save",
  manageUsersAreYouSureDelete: "Are you sure you want to delete this user?",
  manageUsersYesBtn: "Yes",
  manageUsersNoBtn: "No",
  manageUsersNewPassword: "New Password",
  manageUsersNewPasswordRetype: "Retype New Password",
  manageUsersChangePasswordBtn: "Change Password",
  manageUsersPasswordMismatchError: "Passwords do not match.",
  manageUsersPasswordUnknownError: "Unknown Error, please try again.",
  manageUsersPasswordSuccess: "Password has been changed",
  manageUsersAddUserBtn: "Add New User",

  // [sub-component] manage child users - add new user
  addUserTitle: "Add New User",
  addUserUsername: "Username",
  addUserUsernameNotAvailError: "Username is not available.",
  addUserUsernameLengthError: "Username must be at least 8 characters long!",
  addUserEmail: "Email",
  addUserEmailAlreadyUsedError: "This email is already used.",
  addUserEmailInvalidError: "This email doesn't appear valid.",
  addUserPassword: "Password",
  addUserPasswordRetype: "Retype Password",
  addUserPasswordMismatchError: "Passwords do not match.",
  addUserSecondFactorType: "Select Second Factor Type",
  addUserNoFactorSelected: "Nothing Selected",
  addUserNoRolesSelected: "No Role Selected",
  addUserRoles: "Select User Roles",
  addUserUnknownError: "Unknown Error, please try again.",
  addUserCancelBtn: "Cancel",
  addUserCreateBtn: "Add Account",

  // ----------[component] settings
  settingsNav: "User Settings",
  settingsTitle: "User Settings",
  settingsEmailNotifications: "Email me my notification messages",
  settingsPhoneNotifications: "Text me my notification messages",
  settingsSecondFactor: "Change Your Second Factor Type",
  settingsLanguage: "Change Language Preference",
  settingsSaveSuccess: "Settings have been saved.",
  settingsSaveBtn: "Save Settings",

  // ----------[component] sign up
  signUpNav: "Register",
  signUpTitle: "Register",
  signUpUsername: "Username",
  signUpUsernameNotAvailError: "Username is not available.",
  signUpUsernameLengthError: "Username must be at least 8 characters long!",
  signUpEmail: "Email",
  signUpEmailAlreadyUsedError: "This email is already used.",
  signUpEmailInvalidError: "This email doesn't appear valid.",
  signUpPhone: "Mobile Phone",
  signUpPhoneAlreadyUsedError: "This phone number is already used.",
  signUpPhoneInvalidError: "This phone doesn't appear valid.",
  signUpConfirmWith: "Confirm Account Using",
  signUpSecurityQuestion: "Select Security Question",
  signUpSecurityAnswer: "Security Answer",
  signUpSecurityLengthError: "Security Answer must be at least 6 characters long!",
  signUpPassword: "Password",
  signUpPasswordRetype: "Retype Password",
  signUpPasswordMismatchError: "Passwords do not match.",
  signUpSecondFactor: "Sent Pin",
  signUpSecondFactorSent: "We sent you a pin, please enter it here and submit the form again.",
  singUpUnknownError: "Unknown Error, please try again.",
  signUpPleaseWait: "Please wait and do not close this. We are preparing your account and will send you a pin that will need to be entered in this form.",
  signUpBtn: "Register",

  // -------------------------------------------------------------------------------------------------------------------
});