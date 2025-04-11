import axios from "axios"

const API = 'http://127.0.0.1:8000/api/v1/place';


export default {
    getAll(){
        return axios.get(API);
    },

    get(id){
        return axios.get(`${API}/${id}`)
    },
    create(data){
        return axios.post(API,data);
    },

    update(id,data){
        return axios.put(`${API}/${id}`, data)
    },
    delete(id) {
        const token = localStorage.getItem('auth_token');
        return axios.delete(`${API}/${id}`, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
      }      
}