import axios from 'axios'

const apiQuizClass = axios.create({
    baseURL: process.env.QUIZCLASS_API
  });

  apiQuizClass.interceptors.request.use(async config => {
    config.headers['Content-type'] = `application/json`;
    config.headers['X-Client-Id'] = localStorage.getItem('@QuizClassClientId');
    config.headers['X-Session-Id'] = localStorage.getItem('@QuizClassSessionId');

    return config;
  });

  export default apiQuizClass;