<template>
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Users</h3>

        <div class="card-tools">
            <button class="btn btn-success" data-toggle="modal" data-target="#userModal">Add New</button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <td>{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td> {{ user.email }} </td>
              <td> {{ user.created_at }} </td>
              <td>
                  <a class="btn btn-info text-white"><i class="fa fa-edit"></i></a>
                  <a class="btn btn-danger text-white"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">Add New User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="addUser" @keydown="form.onKeydown($event)">
      <div class="modal-body">
            <div class="form-group">
              <input v-model="form.name" type="text" name="name"
                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Name">
              <has-error :form="form" field="name"></has-error>
            </div>

            <div class="form-group">
              <input v-model="form.email" type="email" name="email"
                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Email">
              <has-error :form="form" field="email"></has-error>
            </div>

            <div class="form-group">
              <input v-model="form.bio" type="text" name="bio"
                class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }" placeholder="Bio">
              <has-error :form="form" field="bio"></has-error>
            </div>

            <div class="form-group">
                <select name="type" v-model="form.type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                    <option value="" disabled>Select User Role</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                    <option value="author">Author</option>
                </select>
                <has-error :form="form" field="type"></has-error>
            </div>

            <div class="form-group">
              <input v-model="form.password" type="password" name="password"
                class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Password">
              <has-error :form="form" field="password"></has-error>
            </div>          

      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button :disabled="form.busy" type="submit" class="btn btn-primary">Save changes</button>

      </div>
      </form>
    </div>
  </div>
</div>

  </div>      
</template>

<script>
    export default {
        data () {
        return {
          users: {},
          form: new Form({
            name: '',
            email: '',
            password: '',
            remember: false
          })
        }
      },
      methods: {
        loadUsers() {
            axios.get('api/users').then( ({ data }) => (this.users = data.data));
        },
        addUser() {
          // Submit the form via a POST request
          this.form.post('/api/users')
            .then(({ data }) => { console.log(data) })
            }
        },
        created() {
                this.loadUsers();
            }
        }
</script>
