export function setAuthData ({ user, originalUser, token }, provider) {
  console.log(user, originalUser, token, provider)

  localStorage.setItem('user', JSON.stringify(user))
  localStorage.setItem('originalUser', JSON.stringify(originalUser))
  localStorage.setItem('token', token)
  localStorage.setItem('provider', provider)
}
