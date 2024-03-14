<template>
    <div class="main-content side-content">
        <div class="container">
            <page-header title="sidebar.profile" :active="false">
                <li class="breadcrumb-item active" aria-current="page">{{ $t('messages.edit_profile') }}</li>
            </page-header>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card custom-card">
                        <div class="card-header rounded-bottom-0 my-3">
                            <div class="card-body">
                                <form class="d-flex flex-column">
                                    <div class="mb-3">
                                        <form-input label="messages.name" type="text"
                                                    :model="user" name="name" :errors="formErrors" @clearErrors="clearInput"/>
                                    </div>
                                    <div class="mb-3">
                                        <form-input label="messages.email" type="email"
                                                    :model="user" name="email" :errors="formErrors" @clearErrors="clearInput"/>
                                    </div>
                                    <div class="mb-3">
                                        <form-input label="messages.password" type="password"
                                                    :model="user" name="password" :errors="formErrors" @clearErrors="clearInput"/>
                                    </div>
                                    <div class="mb-3">
                                        <form-input label="messages.password_confirm" type="password"
                                                    :model="user" name="password_confirmation" :errors="formErrors" @clearErrors="clearInput"/>
                                    </div>
                                    <div class="mb-3">
                                        <form-input label="messages.phone" type="number"
                                                    :model="user" name="phone" :errors="formErrors" @clearErrors="clearInput"/>
                                    </div>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button class="btn ripple btn-primary me-2" :disabled="submitFormDisabled" @click.prevent="submit">{{ $t('forms.save') }}</button>
                                        <cancel-button/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import UserApi from '@api/user.api';

    export default {
        name: 'edit-profile',
        data(){
          return {
              user:{}
          }
        },
        created() {
            this.user = this.$store.userStore.getUserData
        },
        methods: {
            submit() {
                UserApi.update(this.user)
                    .then(resp => {
                        this.$store.userStore.setUserData(resp.data.data)
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.formErrors = error.response.data.errors
                        }
                    });
            },
        },
    }

</script>
