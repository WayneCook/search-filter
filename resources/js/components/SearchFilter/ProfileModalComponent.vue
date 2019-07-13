<template>
  <v-layout row justify-center>      
    <v-dialog v-model="dialog" persistent max-width="550px">
      <v-card>
        <v-img
            class='dark-overlay'
            height="100%"
            src="https://cdn.vuetifyjs.com/images/cards/server-room.jpg"
          >
            <v-container class='image-container' fluid fill-height>
                <v-layout justify-start align-center>
                    <v-flex auto>  
                        <v-avatar
                            class="elevation-5 custom-avatar"
                            :size="$vuetify.breakpoint.smAndUp ? 144 : 98"
                            >
                                <v-progress-circular
                                    v-if='loading'
                                    indeterminate
                                    color="purple"
                                ></v-progress-circular>
                                <v-img v-if='!loading' class='employee-avatar' :src="image_url"></v-img>
                        </v-avatar>
                    </v-flex>
                    <v-flex col>
                        <p d-block ma-0 class='display-1 white--text'>{{ item.first_name }} {{ item.last_name }} </p>
                        <p class='subheading font-weight-light white--text'>{{ item.position }}</p>                
                    </v-flex>              
                </v-layout>
            </v-container>     
          </v-img>
        <v-card-text>
          <v-container>
            <v-layout align-center justify-space-between row>
              <v-flex col>
                <v-btn round block color="purple darken-2" dark>
                    <v-icon>email</v-icon>
                    Profile
                </v-btn>
              </v-flex>
               <v-flex col>
                <v-btn round block color="purple darken-2" dark>
                    <v-icon>account_circle</v-icon>
                    Email
                </v-btn>
              </v-flex>          
            </v-layout>
          </v-container>         
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="purple darken-2" flat @click="close()">Close</v-btn>  
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
  export default {
    data: () => ({
        loading: false,
        dialog: false,
        item: Object,
        url: 'https://randomuser.me/api/',
        image_url: ''
    }),
    methods: {
        getUserImage() {
            this.loading = true;
             
            axios.get(this.url)
            .then((res) => {
                this.image_url = res.data.results[0].picture.large  
            })
            .catch((error) => {
                if (error.response) {
                console.log(error.response.data.errors);         
                }
            })
            .finally(() => {
                this.loading = false
            })
        },
        close(){
            this.dialog = false;
            this.image_url = '';
        }
    },
    mounted() {
        this.$eventHub.$on('show-modal', item => {  
        this.getUserImage();
        this.item = item;
        this.dialog = true;
    });
    }
  }
</script>

<style scoped>


.dark-overlay:after {

    content: '';
    width: 100%;
    height: 100%;
    background-color: hsla(0, 0%, 0%, 0.54);
    position: absolute;
    display: block;
    z-index: -1;
}

.employee-avatar {
    border: 3px solid white;
}

.custom-avatar {
    border: 3px solid white;
}

p {
    margin-bottom: 0px;
}



</style>
