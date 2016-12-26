define(function() {
  return {
    languages: [
    ],
    nonLanguages: [
      {name: "English", code: "en"},
      {name: "Français", code: "fr"}
    ],
    server: {
      domain: "http://ico.equibit.org",
      sockets: "ws://ico.equibit.org/ws/"
    },
    webSockets: false,
    general: {
      requireEmail: true,
      requirePhone: false,
      signUpWithEmail: true,
      signUpWithPhone: false,
      phoneMask: '+1(###) ###-####',
      hasBlog: false,
      hasFAQs: false,
      hasSubUsers: false,
      hasAPI: false,
      hasChat: false,
      usernameMinLength: 4,
      securityAnswerMinLength: 4
    }
  }
});