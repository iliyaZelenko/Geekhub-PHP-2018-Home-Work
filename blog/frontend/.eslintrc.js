module.exports = {
  root: true,
  env: {
    browser: true,
    node: true
  },
  extends: [
    // https://github.com/vuejs/eslint-plugin-vue#priority-a-essential-error-prevention
    // consider switching to `plugin:vue/strongly-recommended` or `plugin:vue/recommended` for stricter rules.
    'standard',
    'plugin:vue/recommended',
    // TODO удалить связанные не нужные пакеты
    // 'plugin:node/recommended',
    'plugin:promise/recommended',
    // "typescript"
  ],
  // required to lint *.vue files
  plugins: [
    'vue'
  ],
  rules: {
    // 'space-infix-ops': 'off', // WORKAROUND
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off'
  },
  parserOptions: {
    // parser: 'babel-eslint'
    parser: 'typescript-eslint-parser'
  }
}
