define({
  // -------------------------------------------[miscellaneous]---------------------------------------------------------

  // ----------[system] variables
  locale: 'fr',
  timestamp: 'D MMMM, YYYY',
  timestampDetailed: 'D MMMM, YYYY, h:mm:ss a',

  // ----------[navigation] extras
  navSelectLanguage: "Choisir la langue",
  navUserMenu: "Menu de l'utilisateur",
  navAdminMenu: "Menu admin",
  navLogout: "Déconnecter",

  // ----------[messages]
  noMessages: "Pas de messages",

  // ----------[alert] messages
  warning: "Attention",
  error: "Erreur",
  sessionExpired: "Votre session a expiré, S'il vous plaît Connectez-vous encore!",

  // ----------[password] strength
  longerPasswordNeeded: "Mot de passe plus recommandé!",
  strongPassword: "Ce mot de passe est forte!",
  okPassword: "Ce mot de passe est juste OK!",
  weakPassword: "Ce mot de passe est faible!",

  // -------------------------------------------[database]--------------------------------------------------------------

  // ----------[database] factors
  i18nNone: "Aucun",
  i18nNoneText: "Aucun second facteur",
  i18nEmail: "Email",
  i18nEmailText: "Envoyer un message Pin",
  i18nSMS: "SMS",
  i18nSMSText: "Texte du message Pin",

  // ----------[database] roles
  i18nAdmin: "Administrateur",
  i18nAdminText: "La gestion du système",
  i18nUser: "Utilisateur",
  i18nUserText: "Utilisateur général",
  i18nManage: "Gérer",
  i18nManageText: "Gérer les utilisateurs",

  // -------------------------------------------[admin page]------------------------------------------------------------

  // ----------[page] admin users
  adminUserNav: "Comptes utilisateur",
  adminUserTitle: "Comptes utilisateur",
  adminUsersSearch: "Chercher",
  adminUsersUsername: "Nom d'utilisateur",
  adminUsersEmailConfirmed: "Confirmé",
  adminUsersEmail: "Email",
  adminUsersPhoneConfirmed: "Confirmé",
  adminUsersPhone: "Téléphone",
  adminUsersLock: "Compte bloqué",
  adminUsersLanguage: "Language",
  adminUsersLocked: "Bloqué",
  adminUsersNotLocked: "Non bloqué",
  adminUsersCreated: "Créé",
  adminUsersOptions: "Options",
  adminUsersNotify: "Notifier",
  adminUsersUserOptions: "Options utilisateur",
  adminUsersUserNotify: "Notifier",
  adminUsersNoUsers: "Aucun utilisateur de recherche",
  // [sub-page] users options
  userOptionsTitle: "Options utilisateur pour",
  userOptionsLock: "Compte bloqué",
  userOptionsNotifications: "Notifications par email",
  userOptionsEmailConfirmed: "Email Confirmé",
  userOptionsPhoneConfirmed: "Téléphone Confirmé",
  userOptionsDeleteBtn: "Supprimer l'utilisateur",
  userOptionsSaveBtn: "Sauvegarder les modifications",
  // [sub-page] users options
  userNotifyTitle: "Notifications de l'utilisateur",
  userNotifySubject: "Objet de la notification",
  userNotifyMessage: "Message de notification",
  userNotifySent: "Notification envoyée.",
  userNotifyUnknownError: "Erreur inconnue, contactez-nous.",
  userNotifyMissingInformation: "Les informations manquantes à envoyer.",
  userNotifySend: "Envoyer une notification",

  // ----------[page] admin text variables
  adminTextNav: "Text Variables",
  adminTextTitle: "Edit Email and SMS Text Variables",
  adminTextEmailMe: "Test The Following by Email",
  adminTextSMSMe: "Test The Following by SMS",
  adminTextVariables: "Text Variables",
  adminTextEmailSignUp: "Sign Up",
  adminTextEmailLogin2F: "Login 2F",
  adminTextEmailLoginFailed: "Login Failed",
  adminTextEmailForgot: "Forgot Password",
  adminTextEmailChange: "Change Email",
  adminTextEmailNotification: "Notification",
  adminTextSMSSignUp: "Sign Up",
  adminTextSMSLogin2F: "Login 2F",
  adminTextSMSLoginFailed: "Login Failed",
  adminTextSMSForgot: "Forgot Password",
  adminTextSMSChange: "Change Phone",
  adminTextSMSNotification: "Notification",

  // ----------[page] admin faqs
  adminFAQsNav: "Modifier FAQ",
  adminFAQsTitle: "Modifier FAQ",
  adminFAQsAddNew: "Ajouter une nouvelle FAQ",
  adminFAQsEdit: "Modifier",
  adminFAQsDelete: "Effacer",
  // [sub-page] new faq
  newFAQTitle: "Ajouter une nouvelle FAQ",
  newFAQQuestion: "Nouvelle question",
  newFAQAnswer: "Nouvelle réponse",
  newFAQLanguage: "Sélectionnez la langue d'affichage",
  newFAQSaved: "Nouvelle FAQ a été sauvée!",
  newFAQUnknownError: "Erreur inconnue, contactez-nous.",
  newFAQMissingInformation: "Nouvelle question ou la réponse ne peut pas être vide.",
  newFAQSave: "Enregistrer Nouvelle FAQ",
  // [sub-page] edit faq
  editFAQTitle: "Modifier FAQ",
  editFAQQuestion: "Modifier question",
  editFAQAnswer: "Modifier réponse",
  editFAQSaved: "FAQ a été sauvée!",
  editFAQUnknownError: "Erreur inconnue, contactez-nous.",
  editFAQMissingInformation: "Question ou réponse ne peut être vide.",
  editFAQSave: "Enregistrer FAQ",

  // ----------[page] admin blog
  adminBlogNav: "Modifier blogue",
  adminBlogTitle: "Modifier blogue",
  adminBlogAddNew: "Ajouter une nouvelle blogue",
  adminBlogEdit: "Modifier",
  adminBlogDelete: "Effacer",
  // [sub-page] new faq
  newBlogTitle: "Ajouter une nouvelle poste",
  newBlogBlogTitle: "Poste titre",
  newBlogImgLink: "URL poste image",
  newBlogShortDesc: "Courte description de poste",
  newBlogPost: "La poste",
  newBlogLink: "Ou URL de poste",
  newBlogLanguage: "Sélectionnez la langue d'affichage",
  newBlogSaved: "Nouveau poste a été enregistré",
  newBlogUnknownError: "Erreur inconnue, contactez-nous.",
  newBlogMissingInformation: "Nouveau titre de poste ou une brève description ne peut être vide.",
  newBlogSave: "Enregistrer nouveau poste",
  // [sub-page] edit faq
  editBlogTitle: "Modifier poste",
  editBlogBlogTitle: "Poste titre",
  editBlogImgLink: "URL poste image",
  editBlogShortDesc: "Courte description de poste",
  editBlogPost: "La poste",
  editBlogLink: "Ou URL de poste",
  editBlogSaved: "Poste a été enregistré",
  editBlogUnknownError: "Erreur inconnue, contactez-nous.",
  editBlogMissingInformation: "Nouveau titre de poste ou une brève description ne peut être vide.",
  editBlogSave: "Enregistrer poste",
  // -------------------------------------------[page]------------------------------------------------------------------

  // ----------[page] blog
  pageBlogNav: "Notre blogue",
  pageBlogTitle: "Notre blogue",
  pageBlogReadMore: "Lire billets",

  // ----------[page] faqs
  pageFAQsNav: "FAQs",
  pageFAQsTitle: "FAQs",
  pageFAQsSearch: "Chercher",

  // ----------[page] chat
  pageChatNav: "Chat",
  pageChatTitle: "Chat",
  pageChatUsername: "Username",
  pageChatMessage: "Message to Send...",
  pageChatSendBtn: "Send",

  // -------------------------------------------[component]-------------------------------------------------------------

  // ----------[component] notification
  notificationComponentNoMessages: "No Messages",
  notificationComponentDelete: "Del",

  // ----------[component] chat
  chatComponentNew: "New",
  chatComponentEnterSend: "Hit Enter to Send...",
  chatComponentJoin: "has joined the chat",
  chatComponentLeft: "has left the chat",

  // ----------[component] change phone
  changePhoneNav: "Change Phone Number",
  changePhoneTitle: "Change Phone Number",
  changePhoneNew: "New Mobile Phone Number",
  changePhoneAlreadyUsedError: "This phone number is already used.",
  changePhoneInvalidError: "This phone number doesn't appear valid.",
  changePhoneSecondFactor: "Sent Pin",
  changePhoneSecondFactorSent: "We sent you a pin, please enter it here and submit the form again.",
  changePhoneSuccess: "Phone Number has Been Changed.",
  changePhonePleaseWait: "Please wait and do not close this. We are sending you a pin that will need to be entered in this form.",
  changePhoneBtn: "Change Phone Number",

  // ----------[component] change email
  changeEmailNav: "Changer l'e-mail",
  changeEmailTitle: "Changer de compte Email",
  changeEmailNew: "Nouvelle adresse courriel",
  changeEmailAlreadyUsedError: "Cet e-mail est déjà pris.",
  changeEmailInvalidError: "Cet e-mail ne semble pas valide.",
  changeEmailSecondFactor: "Pin Envoyés",
  changeEmailSecondFactorSent: "Nous vous avons envoyé une code, s'il vous plaît entrer ici et de soumettre à nouveau le formulaire.",
  changeEmailSuccess: "Email a été modifié.",
  changeEmailPleaseWait: "S'il vous plaît attendre et ne fermez pas. Nous vous envoyons une broche qui devra être saisie dans ce formulaire.",
  changeEmailBtn: "Changer de compte Email",

  // ----------[component] change password
  changePasswordNav: "Changer le mot de passe",
  changePasswordTitle: "Changer le mot de passe",
  changePasswordCurrent: "Mot de passe actuel",
  changePasswordNew: "Nouveau mot de passe",
  changePasswordRetype: "Re-taper le nouveau mot de passe",
  changePasswordMismatchError: "Les mots de passe ne correspondent pas.",
  changePasswordError: "Erreur de mot de passe actuel",
  changePasswordSuccess: "Le mot de passe a été changé",
  changePasswordBtn: "Changer le mot de passe",

  // ----------[component] change question some of following is also used in the sign up component
  changeQuestionNav: "Modifier la question de sécurité",
  changeQuestionTitle: "Modifier la question de sécurité",
  changeQuestionSelect: "Sélectionnez Nouvelle Question de sécurité",
  changeQuestionNoSelect: "Sélectionnez Nouvelle Question",
  // below used in sign up component and forgot password view model
  changeQuestionQuestions: [
    "Quel est le prénom de la personne que vous d'abord embrassé?",
    "Quel est le nom de la réception de votre mariage lieu a eu lieu?",
    "Quel est le nom de votre ami d'enfance préféré?",
    "Dans quelle ville votre mère et votre père ne se rencontrent?",
    "Quel était votre sport favori à l'école secondaire?"],
  changeQuestionError: "Vous devez sélectionner une nouvelle question",
  changeQuestionAnswer: "Réponse Modifier sécurité",
  changeQuestionLengthError: "Réponse de sécurité doit être d'au moins 6 caractères!",
  changeQuestionUnknownError: "Erreur inconnue, contactez-nous.",
  changeQuestionSuccess: "Votre nouvelle question et réponse de sécurité sauvés!",
  changeQuestionBtn: "Enregistrer Nouvelles Questions et réponses",

  // ----------[component] forgot password
  forgotPasswordTitle: "Mot de passe oublié",
  forgotPasswordUsernameOrEmail: "Nom d'utilisateur ou email",
  forgotPasswordSecondFactor: "Pin Envoyés",
  forgotPasswordNew: "Nouveau mot de passe",
  forgotPasswordRetype: "Re-taper le nouveau mot de passe",
  forgotPasswordMismatchError: "Les mots de passe ne correspondent pas.",
  forgetPasswordError: "Erreur inconnue, s'il vous plaît essayer à nouveau.",
  forgetPasswordSuccess: "Mot de passe changé.",
  forgotPasswordPleaseWait: "S'il vous plaît attendre et ne fermez pas. Nous vous envoyons une broche qui devra être saisie dans ce formulaire.",
  forgotPasswordBtn: "Réinitialiser le mot de passe",

  // ----------[component] login
  loginNav: "Se connecter",
  loginTitle: "Se connecter",
  loginUsername: "Nom d'utilisateur",
  loginPassword: "Mot de passe",
  loginForgotPasswordLink: "Mot de passe oublié?",
  loginSecondFactor: "Pin Envoyés",
  loginGeneralError: "Erreur de connexion, essayez à nouveau!",
  loginBtn: "Se connecter",

  // ----------[component] manage child users
  manageUsersNav: "Gérer les utilisateurs",
  manageUsersTitle: "Gérer les utilisateurs",
  manageUsersNoUsers: "Aucun utilisateur pour gérer",
  manageUsersUsername: "Nom d'utilisateur",
  manageUsersSecondFactorType: "Type de 2ème facteur",
  manageUsersRoles: "Rôles de l'utilisateur",
  manageUsersActions: "Actes",
  manageUsersNoFactorSelected: "Rien sélectionné",
  manageUsersNoRolesSelected: "Aucun rôle sélectionné",
  manageUsersDeleteBtn: "Effacer",
  manageUsersNewPasswordBtn: "Nouveau mot de passe",
  manageUsersSaveBtn: "Sauvegarder",
  manageUsersAreYouSureDelete: "Êtes-vous sûr de vouloir supprimer cet utilisateur?",
  manageUsersYesBtn: "Oui",
  manageUsersNoBtn: "Non",
  manageUsersNewPassword: "Nouveau mot de passe",
  manageUsersNewPasswordRetype: "Re-taper le nouveau mot de passe",
  manageUsersChangePasswordBtn: "Changer le mot de passe",
  manageUsersPasswordMismatchError: "Les mots de passe ne correspondent pas.",
  manageUsersPasswordUnknownError: "Erreur inconnue, contactez-nous.",
  manageUsersPasswordSuccess: "Le mot de passe a été changé",
  manageUsersAddUserBtn: "Ajouter un nouvel utilisateur",

  // [sub-component] manage child users - add new user
  addUserTitle: "Ajouter un nouvel utilisateur",
  addUserUsername: "Nom d'utilisateur",
  addUserUsernameNotAvailError: "Le nom d'utilisateur n'est pas disponible.",
  addUserUsernameLengthError: "Nom d'utilisateur doit être au moins 8 caractères!",
  addUserEmail: "Email",
  addUserEmailAlreadyUsedError: "Cet e-mail est déjà pris.",
  addUserEmailInvalidError: "Cet e-mail ne semble pas valide.",
  addUserPassword: "Mot de passe",
  addUserPasswordRetype: "Retaper le mot de passe",
  addUserPasswordMismatchError: "Les mots de passe ne correspondent pas.",
  addUserSecondFactorType: "Sélectionnez 2ème type de facteur",
  addUserNoFactorSelected: "Rien sélectionné",
  addUserNoRolesSelected: "Aucun rôle sélectionné",
  addUserRoles: "Sélectionnez des rôles de l'utilisateur",
  addUserUnknownError: "Erreur inconnue, contactez-nous.",
  addUserCancelBtn: "Annuler",
  addUserCreateBtn: "Ajouter le compte",

  // ----------[component] settings
  settingsNav: "Paramètres utilisateur",
  settingsTitle: "Paramètres utilisateur",
  settingsEmailNotifications: "Envoyez-moi mes messages de notification",
  settingsSecondFactor: "Changer votre type Second facteur",
  settingsLanguage: "Préférence Changer de langue",
  settingsSaveSuccess: "Les paramètres ont été sauvegardés.",
  settingsSaveBtn: "Enregistrer les paramètres",

  // ----------[component] sign up
  signUpNav: "Créer un compte",
  signUpTitle: "Créer un compte",
  signUpUsername: "Nom d'utilisateur",
  signUpUsernameNotAvailError: "Le nom d'utilisateur n'est pas disponible.",
  signUpUsernameLengthError: "Nom d'utilisateur doit être au moins 8 caractères!",
  signUpEmail: "Email",
  signUpEmailAlreadyUsedError: "Cet e-mail est déjà pris.",
  signUpEmailInvalidError: "Cet e-mail ne semble pas valide.",
  signUpPhone: "Mobile Phone",
  signUpPhoneAlreadyUsedError: "This phone number is already used.",
  signUpPhoneInvalidError: "This phone doesn't appear valid.",
  signUpConfirmWith: "Confirm Account Using",
  signUpSecurityQuestion: "Sélectionnez Question de sécurité",
  signUpSecurityAnswer: "Réponse de sécurité",
  signUpSecurityLengthError: "Réponse de sécurité doit être d'au moins 6 caractères!",
  signUpPassword: "Mot de passe",
  signUpPasswordRetype: "Retaper le mot de passe",
  signUpPasswordMismatchError: "Les mots de passe ne correspondent pas.",
  signUpSecondFactor: "Pin Envoyés",
  signUpSecondFactorSent: "Nous vous avons envoyé une épingle, s'il vous plaît entrer ici et de soumettre à nouveau le formulaire.",
  singUpUnknownError: "Erreur inconnue, contactez-nous.",
  signUpPleaseWait: "S'il vous plaît attendre et ne fermez pas. Nous préparons votre compte et vous enverrons une broche qui devra être saisie dans ce formulaire.",
  signUpBtn: "Créer le compte",

  // -------------------------------------------------------------------------------------------------------------------
});