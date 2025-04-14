import axios from 'axios'

// 設定 baseURL 和 withCredentials
axios.defaults.baseURL = '/'
axios.defaults.withCredentials = true

// 加上攔截器
axios.interceptors.response.use(
  response => response,
  error => {
    // 👉 只針對 /me 回傳 401 的情況「靜默處理」
    if (error.config?.url === '/me' && error.response?.status === 401) {
      return Promise.resolve({ data: null }) // 假裝成功回傳 null，避免 console 顯示錯誤
    }

    return Promise.reject(error) // 其他錯誤照常處理
  }
)

export default axios
