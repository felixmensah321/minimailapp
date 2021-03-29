<template>
    <div>
        <div class="card-header">Mails</div>
        <div class="card-body">
            <table class="table table-sm table-bordered table-striped">
                <thead>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Subject</th>
                    <th>Body</th>
                    <th>Date sent</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="mail in mails">
                    <td>{{ mail.from }}</td>
                    <td>{{ mail.to }}</td>
                    <td>{{ mail.subject }}</td>
                    <td>{{ mail.body }}</td>
                    <td>{{ mail.created_at | moment("dddd, MMMM Do YYYY") }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
Vue.use(require('vue-moment'));

export default{
    props:['mails'],
    data(){
        return{

        }
    },
    mounted() {
        console.log(this.mails)
    }
}
</script>
