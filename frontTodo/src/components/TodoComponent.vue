<template>
  <div class="container">
    <div class="logout-container">
      <button class="logout-btn" @click="logout"><i class="fas fa-sign-out-alt"></i></button>
    </div>
    <!-- heading -->
    <h1 class="text-center">Lista de tareas</h1>
    <span class="text-center">(Las tareas que agregas son azules)</span>
    <br>
    <br>
    <!-- input -->
    <div class="input-container">
      <input class="task-input" type="text" v-model="task" :maxlength="255" placeholder="Nueva tarea..." />
      <button class="add-btn" @click="submitTask">Agregar</button>
    </div>
    <br>
    <!-- table -->
    <table class="table-container">
      <thead>
        <tr style="display: none;">
          <th>Title</th>
          <th>Delete</th>
          <th>Edit</th>
          <th>Check</th>
        </tr>
      </thead>
      <tbody>
          <tr
            v-for="(task, index) in tasks"
            :key="index"
            class="table-row"
            :class="{ completed: task.completed, 'my-task': task.is_mine }"
          >
            <td class="table-cell task-title">
              <span v-if="editingIndex !== index">{{ task.title }}</span>
              <div v-else>
                <input
                  type="text"
                  v-model="taskTitle"
                  @keyup.enter="submitTask"
                  @keyup.esc="cancelEdit"
                  :maxlength="255"
                />
              </div>
              <br>
              <div v-if="!task.is_mine" class="subtitle">
                <sub v-if="task.user_name.length<10">{{ task.user_name }}</sub>
                <sub v-else>{{ task.user_name.substring(0,10)+".." }}</sub>
              </div>
            </td>
            <td class="table-cell">
              <button class="del-btn" @click="deleteTask(index)">
                <i class="fas fa-times"></i>
              </button>
            </td>
            <td class="table-cell">
              <button v-if="editingIndex !== index" class="edit-btn" @click="changeTask(index)">
                <i class="fas fa-pencil-alt"></i>
              </button>
              <button v-else class="edit-btn" @click="submitTask">
                <i class="fas fa-save"></i>
              </button>
            </td>
            <td class="table-cell">
              <button class="edit-btn" @click="checkTask(index)">
                <i class="fas fa-check"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <Spinner v-if="loading" />
  </div>
</template>

<script>
  import { ref, onMounted, onBeforeUnmount } from "vue";
  import env from "@/env.js";
  import Spinner from "vue-spinner/src/ScaleLoader.vue";
  import router from '../router';
  import { io } from "socket.io-client";
  import { notify } from "@kyvg/vue3-notification";


  const tasks = ref([]);
  const task = ref("");
  const taskTitle = ref("");
  const loading = ref(true);
  const editingIndex = ref(-1);
  const ws = ref(null);

  export default {
    name: "AppTodo",
    components: {
      Spinner,
    },
    setup() {
      const fetchTasks = async () => {
        const response = await fetch(`${env.urlTodo}`,{
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem('t')}`,
          },
        });
        if(response.status === 401) logout();
        const tasksJson = await response.json();
        tasks.value = tasksJson.tasks;
        loading.value = false;
      };

      const deleteTask = async (index) => {
        const taskSel = tasks.value[index];
        loading.value = true;
        tasks.value.splice(index, 1);
        const response = await fetch(`${env.urlTodo}/${taskSel.id}`, {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem('t')}`,
          }
        });
        if(response.status === 401) logout();
        sendEvent('delete',{index: index,sender: localStorage.getItem('u')});
        loading.value = false;
      };

      const changeTask = (index) => {
        editingIndex.value = index;
        taskTitle.value = tasks.value[index].title;
      };

      const checkTask = async (index) => {
        let completedTask = tasks.value[index];
        completedTask.completed ^= true;
        const taskResult = await editRequest(completedTask);
        if(taskResult?.id) tasks.value[index] = completedTask;
      };

      const editRequest = async (editedTask) => {
        if (!editedTask.id) return;
        loading.value = true;
        const response = await fetch(`${env.urlTodo}/${editedTask.id}`, {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem('t')}`,
          },
          body: JSON.stringify(editedTask),
        });
        if(response.status === 401) logout();
        const tasksJson = await response.json();
        sendEvent('edit',{
          id: editedTask.id,
          title: editedTask.title,
          completed: editedTask.completed,
          sender: localStorage.getItem('u')
        });
        loading.value = false;
        return tasksJson;
      };

      const submitTask = async () => {
        if (!task.value.length && !taskTitle.value.length) return;
        loading.value = true;
        if (editingIndex.value !== -1) {
          const editedTask = tasks.value[editingIndex.value];
          editedTask.title = taskTitle.value.trim();
          taskTitle.value = "";
          editingIndex.value = -1;
          tasks.value[editingIndex.value] = editedTask;
          return await editRequest(editedTask);
        }

        const tasksJson = await fetch(`${env.urlTodo}`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem('t')}`,
          },
          body: JSON.stringify({
            title: task.value.trim(),
            description: "",
            completed: false,
          }),
        })
        .then(response => {
          if(response.status === 401) logout();
          return response.json();
        })
        tasks.value.push({...tasksJson,is_mine: true});
        sendEvent('add',{
          ...tasksJson,
          is_mine: false,
          user_name: localStorage.getItem('u')
        });
        task.value = '';
        loading.value = false;
      };

      const cancelEdit = () => {
        editingIndex.value = -1;
        taskTitle.value = "";
      };

      const connectWebSocket = async () => {
        ws.value = io("localhost:4000",{reconnectionAttempts:1});
        ws.value.on('connect_error', () => {
          ws.value.close(); // stop further attempts to reconnect
          notify({
            title: 'No hay conexion',
            text: 'Cambios en tiempo real no establecidos',
            type: 'error'
          });
        });
        ws.value.addEventListener("delete", (data) => {
          if(editingIndex.value === data.index) cancelEdit();
          const title = tasks.value[data.index].title
          tasks.value.splice(data.index, 1);
          notify({
            title: title,
            text: 'eliminado por: '+data.sender,
            type: 'error'
          });
        });

        ws.value.addEventListener("add", (task) => {
          tasks.value.push(task);
          notify({
            title: task.title,
            text: 'agregado por: '+task.user_name,
            type: 'success'
          });
        });

        ws.value.addEventListener("edit", (data) => {
          const editedTask = tasks.value.find(task => task.id === data.id);
          if (editedTask) {
            notify({
              title: editedTask.title,
              text: 'editado por: '+data.sender
            });
            editedTask.completed = data.completed;
            editedTask.title = data.title;
          }
          // Handle received messages, e.g., update the task list
        });
      };

      const sendEvent = (type, task) => {
        if (ws.value) ws.value.emit("event", { type, task });
      }

      const logout = () => {
        localStorage.removeItem('t');
        localStorage.removeItem('u');
        router.push("/login").then(() => {
          router.go();
        });
      };

      fetchTasks();

      onMounted(() => connectWebSocket());

      onBeforeUnmount(() =>{ if(ws.value) ws.value.close() });

      return {
        task,
        tasks,
        loading,
        deleteTask,
        changeTask,
        checkTask,
        submitTask,
        cancelEdit,
        editingIndex,
        taskTitle,
        logout,
      };
    }
  }
</script>
<style scoped>
  /* Large screens (desktop) */
  @media (min-width: 992px) {
    h1 {
      font-size: 36px;
      margin-bottom: 20px;
      color: #4cacbc;
    }

    input[type="text"] {
      height: 40px;
      padding: 10px;
      font-size: 16px;
      border: none;
      border-bottom: 2px solid #a0d995;
      background-color: #f6e3c57a;
      color: #333;
      min-width: 40%;
    }

    .add-btn {
      width: 100px;
      height: 40px;
      font-size: 16px;
      background-color: #6cc4a1;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
    }

    .add-btn:hover {
      background-color: #4cacbc;
      transform: scale(1.1);
    }

    .del-btn,
    .edit-btn {
      font-size: 20px;
      margin: 0px 5px;
      /* background-color: #f6e3c5; */
      color: #333;
      border: none;
      border-radius: 50%;
      padding: 12px;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
    }

    .del-btn:hover,
    .edit-btn:hover {
      background-color: #a0d995;
      color: #fff;
      transform: scale(1.1);
    }

    td,
    th {
      padding: 10px;
      font-size: 16px;
      border: 1px solid #a0d995;
      text-align: center;
      /* background-color: #f5ecde; */
      color: #333;
    }
  }
  /* Add transparency to the container */
  .container {
    background-color: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.2);
    max-width: 800px;
    opacity: 0.7;
    margin-bottom: 1.5em;
  }

  td:nth-child(2) {
    color: #333;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .table-container {
    display: table;
    width: 100%; /* Adjust the table width as needed */
    border-collapse: collapse;
  }

  .table-row {
    display: table-row;
  }

  .table-cell {
    display: table-cell;
    padding: 5px; /* Adjust the cell padding as needed */
    border: 1px solid #4CACBC; /* Adjust the cell border as needed */
  }

  .task-title {
    width: 60%;
    font-size: 1.2em;
  }

  .completed {
    text-decoration: line-through;
    color: black;
    background-color: #d1d1d162;
  }

  .my-task i, .my-task td{
    color: #4CACBC;
  }

  .logout-container {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 10px;
  }

  .logout-btn {
    /* Añade cualquier estilo adicional para el botón de logout */
    background-color: #f44336;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 16px;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
  }
  .subtitle{
    margin-left: 60%;
  }
</style>
