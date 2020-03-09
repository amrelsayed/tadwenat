<template>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Users</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">

  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Users</h3>

        <div class="card-tools">
            <button @click="newModal" class="btn btn-success" data-toggle="modal" data-target="#userModal">Add New</button>
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
              <th>Type</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <td>{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td> {{ user.email }} </td>
              <td> {{ user.type | capitalize }} </td>
              <td> {{ user.created_at | humanDate }} </td>
              <td>
                  <button @click="editModal(user)" class="btn btn-info text-white"><i class="fa fa-edit"></i></button>
                  <button @click="deleteUser(user.id)" class="btn btn-danger text-white"><i class="fa fa-trash"></i></button>
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
        <button :disabled="form.busy" type="submit" class="btn btn-primary">Save</button>

      </div>
      </form>
    </div>
  </div>
</div>

  </div>
  </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content --> 
      </div>
  <!-- /.content-wrapper -->
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
                type: '',
                bio: '',
                photo: ''
              })
            }
        },
      methods: {
        newModal() {
            this.form.reset();
            $('#userModal').modal('show');
        },
        editModal(user) {
            this.form.reset();
            this.form.fill(user);
            $('#userModal').modal('show');
        },
        loadUsers() {
            axios.get('api/users').then( ({ data }) => (this.users = data.data));
        },
        addUser() {
            this.$Progress.start()
            
            this.form.post('/api/users').then(() => {
                $('#userModal').modal('hide');

                Toast.fire({
                  icon: 'success',
                  title: 'User created successfully'
                })

                this.loadUsers();    
            })

            this.$Progress.finish();
        },
        deleteUser(id) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {

                this.form.delete('api/users/' + id).then(() => {
                    Toast.fire({
                        icon: 'success',
                        title: 'User has been deleted'
                    });

                    this.loadUsers();
                }).catch(() => {
                     Toast.fire({
                        icon: 'error',
                        title: 'Something went wrong'
                    });
                });
            }
            })
        }
    },
    created() {
        this.loadUsers();
    }
}
</script>
