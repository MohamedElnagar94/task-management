<template>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Select Project</label>
                    <select v-model="projectID" @change="getTasks()" class="form-control form-control-lg">
                        <option value="">Select Project</option>
                        <option v-for="(project,index) in projects" :key="index" :value="project.id">{{project.name}}</option>
                    </select>
                </div>
                <form @submit.prevent="saveTask()" v-if="projectID != ''" class="mb-3">
                    <div class="form-group">
                        <label for="taskName">Task Name</label>
                        <input type="text" autocomplete="false" maxlength="150" minlength="3" v-model="taskName" class="form-control" id="taskName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                <transition-group name="flip-list" tag="div">
                    <table v-if="projectID != ''" class="table table-hover text-center" :key="projectID">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Task Name</th>
                                <th scope="col">Priority</th>
                                <th scope="col">Timestamp</th>
                                <th scope="col" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                style="cursor: cell;"
                                v-for="(task,index) in tasks" :key="index"
                                draggable="true"
                                @dragover="(e) => onDragOver(task, index, e)"
                                @dragend="(e) => finishDrag(task, index, e)"
                                @dragstart="(e) => startDrag(task, index, e)"
                                :class="{'table-info': (task === over.item && task !== dragFrom), [over.dir]: (task === over.item && task !== dragFrom) }"
                            >
                                <th scope="row">{{index + 1}}</th>
                                <td>
                                    <input v-if="editTaskNum == task.id && showTask == true" v-model="newTask" v-on:keyup.enter="saveChanges(task.id)" type="text" class="form-control text-center"/>
                                    <span v-else>{{task.name}}</span>
                                </td>
                                <td>{{task.priority}}</td>
                                <td>{{dateFormate(task.created_at)}}</td>
                                <td><button @click="editTask(task.id)" class="btn btn-success"><i class="fa fa-edit text-white"></i></button></td>
                                <td>
                                    <button class="btn btn-danger" @click="deleteTask(task.id)">
                                        <i class="fa fa-trash text-white"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="tasks.length == 0">
                                <td colspan="6">There is no tasks in this project</td>
                            </tr>
                        </tbody>
                    </table>
                </transition-group>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props:["user_id"],
        data(){
            return{
                projects:[],
                projectID:'',
                taskName:null,
                tasks:[],
                editTaskNum:null,
                newTask:"",
                showTask:false,
                over: {},
                startLoc: 0,
                dragging: false,
                dragFrom: {}
            }
        },
        methods:{
            saveTask:function(){
                axios.post('/api/Tasks',{
                    'projectID':this.projectID,
                    'taskName':this.taskName,
                    'user_id':this.user_id
                }).then((response) => {
                    this.tasks = response.data.projectTasks
                    this.getProjects();
                    this.taskName = null
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Task is created successfully'
                    })
                })
                .catch((error) => {
                    console.log(error);
                })
                .then(() => {
                    // always executed
                });
            },
            getTasks:function(){
                let tasks = this.projects.find(item => item.id == this.projectID)
                this.tasks = tasks.tasks
                this.sortedArray();
            },
            deleteTask:function(id){
                axios.delete('/api/Tasks?taskID=' + id + '&user_id=' + this.user_id).then((response) => {
                    this.tasks = this.tasks.filter(item => item.id != id)
                    this.projects = response.data.projects
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Task is deleted successfully'
                    })
                })
                .catch((error) => {
                    console.log(error);
                })
                .then(() => {
                    // always executed
                });

            },
            dateFormate(date){
                const d = new Date(date)
                const year = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d)
                const month = new Intl.DateTimeFormat('en', { month: 'short' }).format(d)
                const day = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d)
                return `${day}-${month}-${year}`;
                // console.log(`${day}-${month}-${year}`)
            },
            sortedArray(){
                return this.tasks.sort((a, b) => a.priority - b.priority );
            },
            getProjects:function(){
                axios.get('/api/Project?user_id=' + this.user_id).then((response) => {
                    console.log(response.data);
                    this.projects = response.data.projects
                })
                .catch((error) => {
                    console.log(error);
                })
                .then(() => {
                    // always executed
                });
            },
            editTask:function(id){
                this.showTask = !this.showTask
                let task = this.tasks.find(item => item.id == id)
                this.editTaskNum = id
                this.newTask = task.name
            },
            saveChanges:function(id){
                axios.put('/api/Tasks',{
                    'newTask':this.newTask,
                    'user_id':this.user_id,
                    'taskID':id
                }).then((response) => {
                    this.editTaskNum = null
                    this.showTask = false
                    this.projects = response.data.projects
                    let task = this.tasks.find(item => item.id == id)
                    task.name = this.newTask
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Task is updated successfully'
                    })
                })
                .catch((error) => {
                    console.log(error);
                })
                .then(() => {
                    // always executed
                });
            },
            startDrag(item, i, e) {
                this.startLoc = e.clientY;
                this.dragging = true;
                this.dragFrom = item;
                console.log(this.dragFrom);
            },
            finishDrag(item, pos) {
                this.tasks.splice(pos, 1)
                this.tasks.splice(this.over.pos, 0, item);
                this.over = {}

                axios.put('/api/TasksOrder',{
                    'taskOrder':this.tasks,
                    'user_id':this.user_id,
                }).then((response) => {
                    this.projects = response.data.projects
                    this.getTasks();
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Task is ordered successfully'
                    })
                })
                .catch((error) => {
                    console.log(error);
                })
                .then(() => {
                    // always executed
                });
                // console.log(item,pos,this.over.pos)
            },

            onDragOver(item, pos, e) {
                const dir = (this.startLoc < e.clientY) ? 'down': 'up';
                setTimeout(() => {
                    this.over = { item, pos, dir };
                }, 50)

            },
        },
        created(){
            this.getProjects();
        }
    }
</script>
