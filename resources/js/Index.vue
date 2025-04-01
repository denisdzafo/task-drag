<template>
    <div class="task-manager">
        <el-card class="box-card">
            <template #header>
                <el-row class="card-header">
                    <div>
                        <el-button type="primary" @click="addNewTask">Add Task</el-button>
                        <el-button type="primary" @click="addNewProject">Add Project</el-button>
                    </div>
                    <el-select v-model="selectedProject" placeholder="Select project" @change="filterTasks" clearable>
                        <el-option
                            v-for="project in projects"
                            :key="project.id"
                            :label="project.name"
                            :value="project.id"
                        />
                    </el-select>
                </el-row>
            </template>

            <div class="drag-container" v-if="filteredTasks.length > 0">
                <h3>Tasks:</h3>
                <draggable
                    v-model="filteredTasks"
                    item-key="priority"
                    @end="updatePriorities"
                    class="drag-list"
                    handle=".drag-handle"
                >
                    <template #item="{ element }">
                        <div class="drag-item">
                            <el-card shadow="hover" class="draggable-card">
                                <div class="item-content">
                                    <span class="drag-handle">☰</span>
                                    <span class="priority-badge">{{ element.priority }}</span>
                                    <span class="task-name">{{ element.name }}</span>
                                    <el-button
                                        size="small"
                                        @click.stop="editTask(element)"
                                    >Edit</el-button>
                                    <el-button
                                        size="small"
                                        type="danger"
                                        @click.stop="deleteTask(element)"
                                    >Delete</el-button>
                                </div>
                            </el-card>
                        </div>
                    </template>
                </draggable>
            </div>
            <div v-else-if="tasks.length > 0 && selectedProject" class="no-tasks">
                No tasks found for selected project
            </div>
            <div v-else-if="tasks.length === 0" class="no-tasks">
                No tasks available
            </div>
        </el-card>

        <el-card class="box-card" style="margin-top: 20px;">
            <template #header>
                <div class="card-header">
                    <span>Projects</span>
                </div>
            </template>

            <el-table :data="projects" style="width: 100%">
                <el-table-column prop="name" label="Project Name" />
                <el-table-column label="Tasks">
                    <template #default="{ row }">
                        <el-tag
                            v-for="task in row.tasks"
                            :key="task.id"
                            style="margin-right: 5px;"
                        >
                            {{ task.name }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="Actions" width="180">
                    <template #default="{ row }">
                        <el-button size="small" @click="editProject(row)">Edit</el-button>
                        <el-button size="small" type="danger" @click="deleteProject(row)">Delete</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-card>

        <el-dialog v-model="dialogVisible" :title="isEditing ? 'Edit Task' : 'Add Task'">
            <el-form :model="currentTask">
                <el-form-item label="Task Name" prop="name">
                    <el-input v-model="currentTask.name" autocomplete="off" />
                </el-form-item>
                <el-form-item prop="priority">
                    <el-input-number
                        hidden="hidden"
                        v-model="currentTask.priority"
                        :min="1"
                        :max="tasks.length + 1"
                    />
                </el-form-item>
            </el-form>
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="dialogVisible = false">Cancel</el-button>
                    <el-button type="primary" @click="saveTask">Save</el-button>
                </span>
            </template>
        </el-dialog>

        <el-dialog v-model="projectDialogVisible" :title="currentProject.id ? 'Edit Project' : 'Add Project'">
            <el-form :model="currentProject">
                <el-form-item label="Project Name" prop="name">
                    <el-input v-model="currentProject.name" autocomplete="off" />
                </el-form-item>
                <el-form-item label="Select Tasks" prop="tasks">
                    <el-select
                        v-model="currentProject.tasks"
                        multiple
                        placeholder="Select tasks"
                        style="width: 100%"
                    >
                        <el-option
                            v-for="task in tasks"
                            :key="task.id"
                            :label="task.name"
                            :value="task.id"
                        />
                    </el-select>
                </el-form-item>
            </el-form>
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="projectDialogVisible = false">Cancel</el-button>
                    <el-button type="primary" @click="saveProject">Save</el-button>
                </span>
            </template>
        </el-dialog>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
import draggable from 'vuedraggable'
import http from './services/apiEndPoints'

interface Task {
    id: number | string
    name: string
    priority: number
    projects: any[]
}

interface Project {
    id: number | string
    name: string
    tasks: number[]
}

const tasks = ref<Task[]>([])
const projects = ref<Project[]>([])
const filteredTasks = ref<Task[]>([])
const dialogVisible = ref(false)
const projectDialogVisible = ref(false)
const isEditing = ref(false)
const selectedProject = ref<number | string | null>(null)
const currentTask = ref<Task>({
    id: '',
    name: '',
    priority: 1,
    projects: []
})
const currentProject = ref<Project>({
    id: '',
    name: '',
    tasks: []
})

onMounted(() => {
    getTasks()
    getProjects()
})

function getTasks() {
    http.getAllTasks()
        .then(response => {
            tasks.value = response.data.data
            filteredTasks.value = [...tasks.value]
        })
        .catch(e => {
            console.error(e)
            ElMessage.error('Failed to fetch tasks')
        })
}

function getProjects() {
    http.getAllProjects()
        .then(response => {
            projects.value = response.data.data
        })
        .catch(e => {
            console.error(e)
            ElMessage.error('Failed to fetch projects')
        })
}

function filterTasks() {
    if (!selectedProject.value) {
        filteredTasks.value = [...tasks.value]
        return
    }

    filteredTasks.value = tasks.value.filter(task =>
        task.projects.some(project => project.id === selectedProject.value)
    )
}

function addNewTask() {
    currentTask.value = {
        id: '',
        name: '',
        priority: tasks.value.length + 1,
        projects: []
    }
    isEditing.value = false
    dialogVisible.value = true
}

function editTask(task: Task) {
    currentTask.value = { ...task }
    isEditing.value = true
    dialogVisible.value = true
}

function saveTask() {
    const apiCall = isEditing.value
        ? http.updateTask(currentTask.value.id, currentTask.value)
        : http.createTask(currentTask.value)
    apiCall
        .then(() => {
            ElMessage.success(`Task ${isEditing.value ? 'updated' : 'created'} successfully`)
            getTasks()
            dialogVisible.value = false
        })
        .catch(e => {
            console.error(e)
            ElMessage.error(`Failed to ${isEditing.value ? 'update' : 'create'} task`)
        })
}

function deleteTask(task: Task) {
    ElMessageBox.confirm(
        'Are you sure you want to delete this task?',
        'Warning',
        {
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancel',
            type: 'warning',
        }
    )
        .then(() => {
            http.deleteTask(task.id)
                .then(() => {
                    ElMessage.success('Task deleted successfully')
                    getTasks()
                })
                .catch(e => {
                    console.error(e)
                    ElMessage.error('Failed to delete task')
                })
        })
        .catch(() => {})
}

function updatePriorities() {
    const updatedTasks = filteredTasks.value.map((task, index) => ({
        ...task,
        priority: index + 1
    }))

    updatedTasks.forEach(task => {
        http.updateTask(task.id, { priority: task.priority })
            .catch(e => {
                dialogVisible.value = false
                console.error(`Failed to update task ${task.id} priority`, e)
            })
    })

    ElMessage.success('Priorities updated successfully')
    getTasks();
    dialogVisible.value = false
}

function addNewProject() {
    currentProject.value = {
        id: '',
        name: '',
        tasks: []
    }
    projectDialogVisible.value = true
}

function editProject(project: Project) {
    currentProject.value = { ...project }
    projectDialogVisible.value = true
}

function saveProject() {
    if (!currentProject.value.name.trim()) {
        ElMessage.error('Project name is required')
        return
    }

    const projectData = {
        name: currentProject.value.name,
        tasks: currentProject.value.tasks.map(task => typeof task === 'object' ? task.id : task)
    }

    const apiCall = currentProject.value.id
        ? http.updateProject(currentProject.value.id, projectData)
        : http.createProject(projectData)

    apiCall
        .then(() => {
            ElMessage.success(`Project ${currentProject.value.id ? 'updated' : 'created'} successfully`)
            getProjects()
            getTasks()
            projectDialogVisible.value = false
        })
        .catch(e => {
            console.error(e)
            ElMessage.error(`Failed to ${currentProject.value.id ? 'update' : 'create'} project`)
        })
}

function deleteProject(project: Project) {
    ElMessageBox.confirm(
        'Are you sure you want to delete this project?',
        'Warning',
        {
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancel',
            type: 'warning',
        }
    )
        .then(() => {
            http.deleteProject(project.id)
                .then(() => {
                    ElMessage.success('Project deleted successfully')
                    getProjects()
                })
                .catch(e => {
                    console.error(e)
                    ElMessage.error('Failed to delete project')
                })
        })
        .catch(() => {})
}

function getTaskName(taskId: number | string) {
    const task = tasks.value.find(t => t.id === taskId)
    return task ? task.name : 'Unknown task'
}
</script>

<style scoped>
.task-manager {
    padding: 20px;
    max-width: 1000px;
    margin: 0 auto;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.drag-container {
    margin-top: 30px;
}

.drag-list {
    margin-top: 10px;
}

.drag-item {
    margin-bottom: 10px;
    cursor: move;
}

.item-content {
    display: flex;
    align-items: center;
    padding: 10px;
}

.drag-handle {
    margin-right: 15px;
    cursor: grab;
}

.priority-badge {
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    background: #409eff;
    color: white;
    border-radius: 50%;
    margin-right: 15px;
}

.task-name {
    flex-grow: 1;
}

.drag-list {
    user-select: none;
}

.draggable-card {
    transition: transform 0.2s ease;
}

.draggable-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.drag-item {
    cursor: grab;
    user-select: none;
}
.drag-handle {
    cursor: grab;
    padding: 5px;
}
.drag-handle:active {
    cursor: grabbing;
}

.no-tasks {
    text-align: center;
    padding: 20px;
    color: #999;
}
</style>
