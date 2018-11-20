@extends('master')

@section('title', 'Vue.js App')

@section('content')

<div class="jumbotron">
    <h1>@{{ title }}</h1>
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="user in users"  :key="user.id" :class="{active: currentUser === user}">
            <td>@{{ user.name }}</td>
            <td>@{{ user.email }}</td>
            <td>@{{ user.age }}</td>
            <td>@{{ user.department }}</td>
            <td>
                <a class="btn btn-sm btn-primary" v-on:click.prevent="editUser(user)">Edit</a>
                <a class="btn btn-sm btn-danger" v-on:click.prevent="deleteUser(user)">Delete</button>
            </td>
        </tr>
    </tbody>
</table>

<div class="alert alert-success" v-if="showSuccess">
    <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
    <strong>@{{ successMessage }}</strong>
</div>

<div class="jumbotron" v-if="editingUser" >
    <h2>Edit User</h2>
    <div class="form-group">
        <label for="inputName">Name</label>
        <input
            type="text" class="form-control" v-model="currentUser.name"
            name="name" id="inputName" 
            placeholder="Fullname" value="" />
    </div>
    <div class="form-group">
        <label for="inputEmail">Email</label>
        <input
            type="email" class="form-control" v-model="currentUser.email"
            name="email" id="inputEmail"
            placeholder="Email address" value=""/>
    </div>
    <div class="form-group">
        <label for="inputAge">Age</label>
        <input
            type="number" class="form-control" v-model="currentUser.age"
            name="age" id="inputAge"
            placeholder="Age" value=""/>
    </div>
    <div class="form-group">
        <label for="department_id">Department:</label>
        <select class="form-control" id="department_id" name="department_id" v-model="currentUser.department_id" >
            <option v-for="department in departments" v-bind:value="department.id"> @{{ department.name }} </option>
        </select>
    </div>

    <div class="form-group">
        <a class="btn btn-primary" v-on:click.prevent="saveUser()">Save</a>
        <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
    </div>

</div>


@endsection
@section('pagescript')
<script src="js/vue.js"></script>
 @stop  
