let isSsr = typeof process === 'object' && {}.toString.call(process) === '[object process]';

export default isSsr;