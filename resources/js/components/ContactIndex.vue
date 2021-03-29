<template>
    <div>
        <div class="card" v-if="contacts.length > 0">

            <div class="card-header">Contacts</div>
            <div class="card-body">
                <a href="/emails" class="btn btn-success float-right mb-4">All Mails</a>
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="contact in contacts">
                        <td>{{ contact.name }}</td>
                        <td>{{contact.email}}</td>
                        <td align="middle"><a @click="selectedMailContact = {name: contact.name,email: contact.email};requestMail = true" class="text-primary pointer" title="mail"><span><i class="fa fa-envelope"></i></span></a> </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <MailModal :contacts="contacts" :selected="selectedMailContact" v-if="requestMail" :user="userId" @close="requestMail = false"></MailModal>
    </div>
</template>
<script>
import Swal from "sweetalert2";
import MailModal from "./MailModal";
export default {
    props: ['userId'],
    components:{
        MailModal
    },
    data(){
        return{
            contacts: [],
            requestMail: false,
            selectedMailContact:{}
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
    },
    mounted() {
        axios.post(`/api/contact/all/`,{userId: this.userId}).then(response => {

            if (response.status === 200) {
                this.contacts = response.data
            } else {
                // Something went wrong!
                this.abortAndShowError('Something went wrong!', 'HTTP Response: ' + response.status);
            }

        }).catch(error => {
            this.abortAndShowError('Something went wrong!', 'HTTP Response: ' + error.response.status);
        });
    }
}
</script>
<style scoped>
.pointer{
    cursor: pointer;
}
</style>
