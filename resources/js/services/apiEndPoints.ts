import http from './httpCommon';

class endPointApis {
    getAllTasks() {
        return http.get('/task/get');
    }

    createTask(data) {
        return http.post('/task/create', data);
    }

    updateTask(id, data) {
        return http.post('/task/update/'+id, data);
    }

    deleteTask(id) {
        return http.post('/task/delete/'+id);
    }

    createProject(data){
        return http.post('/project/create', data);
    }

    getAllProjects() {
        return http.get('/project/get');
    }

    updateProject(id, data){
        return http.post('/project/update/'+id, data);
    }

    deleteProject(id){
        return http.post('/project/delete/'+id);
    }

}

export default new endPointApis();
