const base_url = 'http://localhost:8696/api'

function url(path) {
    return base_url + path
}
export const login = url('/auth/login')
export const register = url('/auth/register')
export const logout = url('/auth/logout')
export const user = url('/auth/user')
export const update_profile = url('/update_profile')
export const update_password = url('/update_password')
export const verificate_email = url('/user/verify/')
export const reset_password = url('/password/reset/')
export const reset_action = url('/password/reset_action')

