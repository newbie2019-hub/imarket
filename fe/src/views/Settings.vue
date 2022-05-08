<template>
  <div>
    <Navbar />
    <v-layout class="mt-15"></v-layout>
    <v-container class="mt-15">
      <v-row justify="center">
        <!-- <v-col cols="" md="4" lg="4"> </v-col> -->
        <v-col cols="11" md="12" lg="12">
          <v-layout column>
            <h1>Settings</h1>
            <p>Here you can manage your account information</p>
          </v-layout>
          <v-tabs>
            <v-tab>
              <v-icon left> mdi-account </v-icon>
              Personal Info
            </v-tab>
            <v-tab>
              <v-icon left> mdi-lock </v-icon>
              Password
            </v-tab>
            <v-tab>
              <v-icon left> mdi-history </v-icon>
              Account Logs
            </v-tab>

            <v-tab-item>
              <v-card flat>
                <h2 class="mt-8">Basic information</h2>
                <p class="">Please make sure your informations are correct</p>
                <v-form ref="form" v-model="valid" @submit.prevent="update" lazy-validation>
                  <v-row dense class="mt-3">
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <v-text-field
                        prepend-inner-icon="mdi-card-text-outline"
                        class=""
                        hide-details="auto"
                        :rules="required"
                        label="First Name"
                        :loading="isLoading"
                        v-model="data.first_name"
                        required
                        outlined
                      ></v-text-field>
                    </v-col>
                    <v-col cols="11" sm="12" md="6" lg="6">
                      <v-text-field
                        prepend-inner-icon="mdi-card-text-outline"
                        class=""
                        hide-details="auto"
                        label="Middle Name"
                        :loading="isLoading"
                        v-model="data.middle_name"
                        required
                        outlined
                      ></v-text-field>
                    </v-col>
                    <v-col cols="11" sm="12" md="6" lg="6">
                      <v-text-field
                        prepend-inner-icon="mdi-card-text-outline"
                        class=""
                        hide-details="auto"
                        :rules="required"
                        label="Last Name"
                        :loading="isLoading"
                        v-model="data.last_name"
                        required
                        outlined
                      ></v-text-field>
                    </v-col>
                    <v-col cols="11" sm="12" md="6" lg="6">
                      <v-text-field
                        prepend-inner-icon="mdi-map-marker"
                        class=""
                        hide-details="auto"
                        :rules="required"
                        label="Address"
                        :loading="isLoading"
                        v-model="data.address"
                        required
                        outlined
                      ></v-text-field>
                    </v-col>
                    <v-col cols="11" sm="12" md="6" lg="6">
                      <v-select prepend-inner-icon="mdi-gender-male-female" v-model="data.gender" :rules="required" :items="gender" label="Gender" hide-details="auto" outlined></v-select>
                    </v-col>
                    <v-col cols="11" sm="12" md="6" lg="6">
                      <v-text-field
                        prepend-inner-icon="mdi-phone"
                        class=""
                        hide-details="auto"
                        :rules="required"
                        label="Contact Number"
                        :loading="isLoading"
                        v-model="data.contact_number"
                        outlined
                      ></v-text-field>
                    </v-col>
                    <v-col cols="11" sm="12" md="6" lg="6">
                      <v-menu ref="menu" v-model="menu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="data.birthday"
                            label="Date of Birth"
                            prepend-inner-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                            :loading="isLoading"
                            :rules="required"
                            outlined
                          ></v-text-field>
                        </template>
                        <v-date-picker v-model="data.birthday" :active-picker.sync="activePicker" min="1960-01-01" :max="maxBirthDate" @change="save" landscape></v-date-picker>
                      </v-menu>
                    </v-col>
                  </v-row>

                  <v-divider class="mt-7" />
                  <!--- Photo --->
                  <h2 class="mt-8">Photo</h2>
                  <p class="">Your photo will be displayed publicly.</p>
                  <v-row class="mt-3" align="center">
                    <v-avatar color="grey darken-1" size="70" class="ml-3">
                      <img
                        @click.prevent="showProfile"
                        class="img-cover cursor-pointer"
                        v-if="user.info.profile_img"
                        :src="`http://127.0.0.1:8000/images/profiles/${this.user.info.profile_img}`"
                        alt="Profile Image"
                      />
                      <span v-else class="white--text text-h6">{{ this.data.first_name[0] + this.data.last_name[0] }}</span>
                    </v-avatar>
                    <label for="uploadimg" class="ml-3 v-btn v-btn--outlined v-btn--rounded theme--light v-size--default success--text cursor-pointer">Upload</label>
                    <input type="file" id="uploadimg" @change="uploadProfileImage" />
                    <v-dialog v-model="dialogRemove" persistent max-width="400">
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn :disabled="user.info.profile_img == null" color="secondary" class="ml-2" v-bind="attrs" v-on="on" outlined rounded> Remove</v-btn>
                      </template>
                      <v-card>
                        <v-card-title class="text-h5"> Remove Image </v-card-title>
                        <v-card-text> Are you sure you want to remove your current profile image? You cannot undo this action. </v-card-text>
                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn color="grey darken-1" text @click="dialogRemove = false"> Cancel </v-btn>
                          <v-btn @click.prevent="removeImage" color="red darken-1" text :loading="isLoading"> Remove </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog>
                  </v-row>
                  <v-layout class="mt-7" column>
                    <p class="ml-1 mb-0">Attachments</p>
                    <p class="ml-1">
                      <small
                        >Valid ID:<a href="" @click.prevent="show" class="text-decoration-none"> {{ user.info.valid_id }}</a></small
                      >
                    </p>
                  </v-layout>

                  <!--- Photo Section --->
                  <v-divider class="mt-10" />

                  <h2 class="mt-10">Email Address</h2>
                  <p class="">Please ensure that your email is correct</p>
                  <v-row class="mt-3">
                    <v-col cols="12" sm="12" md="6" lg="6">
                      <v-text-field
                        prepend-inner-icon="mdi-email"
                        class=""
                        hide-details="auto"
                        :rules="required"
                        label="Email Address"
                        :loading="isLoading"
                        v-model="data.email"
                        required
                        outlined
                      ></v-text-field>
                    </v-col>
                  </v-row>
                  <v-dialog v-model="dialog" persistent max-width="520">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn color="success" class="mt-5 mb-6" dark v-bind="attrs" v-on="on" depressed> Save Changes </v-btn>
                    </template>
                    <v-card>
                      <v-card-title class="text-h5 pb-0"> Confirm Changes </v-card-title>
                      <v-card-text>
                        <p class="mb-8">Please enter your current password to save your changes.</p>
                        <v-text-field
                          type="password"
                          prepend-inner-icon="mdi-key"
                          class="mt-3"
                          hide-details="auto"
                          v-model="data.current_password"
                          :rules="required"
                          label="Current Password"
                          required
                          outlined
                        ></v-text-field>
                      </v-card-text>
                      <v-card-actions class="pb-4">
                        <v-spacer></v-spacer>
                        <v-btn
                          color="grey darken-1"
                          text
                          @click="
                            $refs.form.reset();
                            dialog = false;
                          "
                        >
                          Cancel
                        </v-btn>
                        <v-btn @click.prevent="update" color="green darken-1" text> Save Changes </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-form>
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card flat class="">
                <v-form class="" ref="formPass" @submit.prevent="savePassword" lazy-validation>
                  <h2 class="mt-8">Change Password</h2>
                  <p class="">Password is a confidential data. Please dont share it with anyone else.</p>
                  <v-text-field
                    type="password"
                    prepend-inner-icon="mdi-key"
                    class="mt-3"
                    hide-details="auto"
                    v-model="passData.current_password"
                    :rules="required"
                    label="Current Password"
                    required
                    outlined
                  ></v-text-field>
                  <v-row dense>
                    <v-col lg="6" md="6" sm="6" cols="12">
                      <v-text-field
                        type="password"
                        prepend-inner-icon="mdi-key-variant"
                        class="mt-3"
                        hide-details="auto"
                        v-model="passData.new_password"
                        :rules="[matchingPasswords]"
                        label="New Password"
                        required
                        outlined
                      ></v-text-field>
                    </v-col>
                    <v-col lg="6" md="6" sm="6" cols="12">
                      <v-text-field
                        type="password"
                        prepend-inner-icon="mdi-key-variant"
                        class="mt-3"
                        hide-details="auto"
                        v-model="passData.confirm_password"
                        :rules="[matchingPasswords]"
                        label="Confirm Password"
                        required
                        outlined
                      ></v-text-field>
                    </v-col>
                  </v-row>

                  <v-card-actions class="mt-3">
                    <v-spacer></v-spacer>
                    <v-btn color="grey darken-2" large text @click="$refs.formPass.reset()"> Cancel </v-btn>
                    <v-btn color="green darken-1" large text type="submit"> Save Changes </v-btn>
                  </v-card-actions>
                </v-form>
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card class="mb-15" flat>
                <h2 class="mt-8">Account Logs</h2>
                <p class="mb-8">To ensure account security, your actions are being logged by the system</p>
                <v-data-table :loading="isLoading" :loading-text="'Retrieving your account logs. Please wait...'" :headers="headers" :items="userLogs" :search="search">
                  <template v-slot:item.event="{ item }">
                    <v-chip :color="getColor(item.event)" dark small>
                      {{ item.event }}
                    </v-chip>
                  </template>
                </v-data-table>
              </v-card>
            </v-tab-item>
          </v-tabs>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
  import Navbar from './components/Navbar.vue';
  import { rules } from '@/assets/js/rules';
  import { mapState } from 'vuex';
  import API from '../store/base/base';
  export default {
    mixins: [rules],
    components: { Navbar },
    data: () => ({
      data: {
        first_name: '',
        middle_name: '',
        last_name: '',
        gender: '',
        contact_number: '',
        address: '',
        birthday: null,
        email: '',
        password: '************',
        current_password: '',
      },
      menu: false,
      activePicker: null,
      valid: true,
      maxBirthDate: '',
      dialogRemove: false,
      dialog: false,
      passData: {
        current_password: '',
        new_password: '',
        confirm_password: '',
      },
      isLoading: false,
      search: '',
      headers: [
        {
          text: 'Activity',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Event', value: 'event' },
        { text: 'Description', value: 'description' },
        { text: 'Timestamp', value: 'created_at' },
      ],
    }),
    async mounted() {
      this.isLoading = true;
      this.maxDate();
      this.setInfo();
      await this.$store.dispatch('auth/getLogs');
      this.isLoading = false;
    },
    methods: {
      matchingPasswords: function () {
        if (this.passData.new_password === this.passData.confirm_password) {
          return true;
        } else {
          return 'Passwords does not match.';
        }
      },
      getColor(event) {
        switch (event) {
          case 'delete':
            return 'error';
          case 'login':
            return 'success';
          case 'update':
            return 'green';
          case 'logout':
            return 'error';
        }
      },
      maxDate() {
        const date = new Date();
        const newDate = (date.getFullYear() - 17).toString() + '-01-01';
        this.maxBirthDate = newDate;
      },
      save(date) {
        this.$refs.menu.save(date);
      },
      async removeImage() {
        this.isLoading = true;
        const { status, data } = await this.$store.dispatch('auth/removeImage');
        this.toastData(status, data);
        await this.$store.dispatch('auth/checkUser');
        await this.$store.dispatch('auth/getLogs');

        this.dialogRemove = false;
        this.isLoading = false;
      },
      async savePassword() {
        const valid = this.$refs.formPass.validate();
        if (valid) {
          const { status, data } = await this.$store.dispatch('auth/changePassword', this.passData);
          this.toastData(status, data);
          this.$refs.formPass.reset();
        }
      },
      async update() {
        this.valid = this.$refs.form.validate();

        if (this.valid) {
          this.isLoading = true;
          const { status, data } = await this.$store.dispatch('auth/update', this.data);
          this.toastData(status, data);
          await this.$store.dispatch('auth/checkUser');
          await this.$store.dispatch('auth/getLogs');

          this.dialog = false;
          this.data.current_password = null;
          this.isLoading = false;
        }
      },
      async uploadProfileImage(event) {
        let formData = new FormData();
        formData.append('img', event.target.files[0]);
        await API.post(`update-profile`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
          .then((response) => {
            this.toastData(200, { msg: 'Profile image updated successfully!' });
          })
          .catch((error) => {
            console.log({ error });
          });
        await this.$store.dispatch('auth/checkUser');
      },
      setInfo() {
        console.log('has changed');
        this.data.first_name = this.user.info.first_name;
        this.data.middle_name = this.user.info.middle_name;
        this.data.last_name = this.user.info.last_name;
        this.data.address = this.user.info.address;
        this.data.contact_number = this.user.info.contact_number;
        this.data.gender = this.user.info.gender;
        this.data.birthday = this.user.info.birthday;
        this.data.email = this.user.email;
      },
    },
    computed: {
      ...mapState('auth', ['user', 'userLogs']),
    },
    watch: {},
  };
</script>
<style>
  .v-tab {
    justify-content: flex-start !important;
  }
</style>
