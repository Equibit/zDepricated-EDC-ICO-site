define(function() {
  return {
    languages: [
    ],
    nonLanguages: [
      {name: "English", code: "en"},
      {name: "Français", code: "fr"}
    ],
    server: {
      domain: "https://ico.equibit.org",
      sockets: "wss://ico.equibit.org/ws/"
    },
    webSockets: false,
    general: {
      requireEmail: true,
      requirePhone: false,
      signUpWithEmail: true,
      signUpWithPhone: false,
      phoneMask: '+1(###) ###-####',
      hasBlog: false,
      hasFAQs: true,
      hasSubUsers: true,
      hasAPI: false,
      hasChat: false,
      usernameMinLength: 4,
      securityAnswerMinLength: 4
    }
  }
});