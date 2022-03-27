import api from './api'

export default {
    uploadPhoto(data) {
        return api.post('user/photo', data)
    }


}
