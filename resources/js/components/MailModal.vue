<template>
    <transition name="modal-fade">
        <div class="modal-wrap">
            <div class="modal-backdrop show"></div>
            <div class="modal" style="display: inline;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="width:720px;">
                        <div class="modal-header">
                            <h5 class="modal-title">Send Mail</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="card card-primary card-outline card-outline-tabs mb-0">
                                <div class="card-body">
                                    <div class="row no-gutters">
                                        <div class="col-md-12">
                                            <label for="contact">Select Contact(s)</label>
                                            <multiselect v-model="selectContact" id="contact" tag-placeholder="Add user" placeholder="Search contact" label="name" track-by="email" :options="contacts" :multiple="true" :taggable="true" @tag="addTag" :custom-label="customLabel"></multiselect>
                                        </div>
                                    </div>
                                    <div class="row no-gutters">
                                        <div class="col-md-12">
                                            <label for="subject" class="d-block">Subject</label>
                                            <input type="text" id="subject" v-model="subject" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row no-gutters">
                                        <div class="col-md-12">
                                            <label for="body" class="d-block">Body</label>
                                            <textarea cols="30" rows="10" v-model="body" id="body" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="row no-gutters">
                                        <div class="col-md-12">
                                            <label for="attachments" class="d-block">attach file</label>
                                            <input type="file" id="attachments" @change="attachments">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="close">Cancel</button>
                            <button type="button" class="btn btn-primary" v-if="subject != null && selectContact != null && body != null" @click="sendEmail()">Send Mail</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
<script>
import Multiselect from 'vue-multiselect'
import Swal from "sweetalert2";
Vue.component('multiselect', Multiselect)
export default {
    props:{
        contacts:{
            type: Array,
            default: []
        },
        selected:{
            type: Object,
            default: {}
        },
        user:{
            type: Number,
            default: 0
        }
    },
    component:{Multiselect},
    data(){
        return{
            selectContact: [
                this.selected
            ],
            subject: null,
            options: [],
            body: null,
            attachment:null
        }
    },
    methods: {
        abortAndShowError(errorMsg, status){
            Swal.fire(
                'Error',
                errorMsg,
                'error'
            )
            this.creationInProgress = false;
        },
        customLabel({name,email}){
            return `${name} - [${email}]`
        },
        close() {
            this.$emit('close');
        },
        confirm() {
            this.$emit('confirm');
        },
        sendEmail(){
            axios.post(`/api/contact/sendmail`, {
                email: this.selectContact,
                subject: this.subject,
                body: this.body,
                attachment: this.attachment,
                user: this.user
            }).then(response => {
                    this.creationInProgress = false
                    Swal.fire(
                        'Success',
                        'Email sent successfully',
                        'success'
                    )
            }).catch(error => {
                this.abortAndShowError('Something went wrong!', 'HTTP Response: ' + error.response.status);
                this.creationInProgress = false
            });
        },
        shareReport(){
            this.$emit('shareReport', {users: this.users, accessLevel: this.selectedAccessLevel,notes: this.notes})
        },
        addTag (newTag) {
            const tag = {
                name: newTag,
                code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
            }
            this.options.push(tag)
            this.value.push(tag)
        },
        attachments(event){
            console.log(event.target.files)
            this.attachment = event.target.files[0].name;
        }
    },
    mounted() {
        this.selectContact.forEach(item => {console.log(item.email)})
    }
}
</script>
<style scoped>
.size-16{
    font-size: 14px !important;
}
.swal2-popup{
    width: 40em !important;
}
.multiselect__tag{
    background: #3490dc !important;
}
.multiselect__option--highlight{
    background: #3490dc !important;
}
.modal-content{
    margin:1% 4% 0 0%;
    width:140% !important;
}
.bulletless {
    padding-left: 0px;
}
.bulletless li {
    list-style-type: none;
    margin-left: 0;
}
.bulletless input {
    float: right;
}
.cust-select{
    border: 1px solid #fff !important;
    border-bottom-color: #111 !important;
    outline: 0 !important;
    padding: 8px 50px 0 8px;
    margin: 1.5% 0 0 0;
}
.multiselect > .multiselect__tags {
    border-radius: 3px !important;
    border: 1px solid #fff;
    border-bottom-color: #111;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

