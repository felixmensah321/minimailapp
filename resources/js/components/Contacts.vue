<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Add Contact</div>
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-8">
                                <form @submit.prevent="addContact()">
                                    <span class="d-block text-danger">{{ error }}</span>
                                    <div class="form-group row">
                                        <label for="reportName" id="reportName" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="name" v-model="name" placeholder="Contact Name" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="dataModel" id="dataModel" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="email" v-model="email" placeholder="Contact Email" required>
                                            <!--                                            <select name="model" v-model="report_model_id" class="form-control">
                                                                                            <option value="0" selected>Please select one</option>
                                                                                            <option v-for="m in modelsData" :value="m.id">{{m.name}}</option>
                                                                                        </select>-->
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary float-right" v-html="createContact" :disabled="enableBtn">Add Contact</button>
                                </form>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 container">
                        <ContactIndex :user-id="currentUser.id" :key="refresh"></ContactIndex>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import ContactIndex from "./ContactIndex";
import Swal from 'sweetalert2'
export default {
    props: ['currentUser'],
    components:{
      ContactIndex,
    },
    data(){
        return{
            name: null,
            email: null,
            error: null,
            creationInProgress: false,
            refresh: 0
        }
    },
    methods:{
        abortAndShowError(errorMsg, status){
            Swal.fire(
                'Error',
                errorMsg,
                'error'
            )
            this.creationInProgress = false;
        },
        addContact(){
            this.creationInProgress = true
            if(this.name === null){
                this.error = 'please specify contact name'
                return false;
            }
            if(this.email === null){
                this.error = 'please specify email'
                return false;
            }

            axios.post(`/api/contact/new`, {
                name: this.name,
                email: this.email,
                currentUser: this.currentUser
            }).then(response => {

                if (response.status === 201) {
                    this.refresh++
                    this.creationInProgress = false
                    // New report was created OK, we can redirect
                } else {
                    // Something went wrong!
                    this.abortAndShowError('Something went wrong!', 'HTTP Response: ' + response.status);
                    this.creationInProgress = false
                }

            }).catch(error => {
                this.abortAndShowError('Something went wrong!', 'HTTP Response: ' + error.response.status);
                this.creationInProgress = false
            });
        }
    },
    computed:{
        createContact() {
            return this.creationInProgress ? "Creating contact..." : "Create Contact";
        },
        enableBtn(){
            return !((this.name != null && this.email != null) && (this.name !== '' && this.email !== ''));
        }
    }
}
</script>
