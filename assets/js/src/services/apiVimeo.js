import axios from 'axios'

const apiVimeo = axios.create({
    baseURL: 'https://api.vimeo.com'
  });

  apiVimeo.interceptors.request.use(async config => {
    config.headers['Content-type'] = `application/json`;
    config.headers['Authorization'] = `Bearer ${process.env.TOKEN_VIMEO}`;
    config.headers['Accept'] = `application/vnd.vimeo.*+json;version=3.4`;

    return config;
  });

  export default apiVimeo;