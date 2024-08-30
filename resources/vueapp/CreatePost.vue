<template>
  <div>
    <div class="card mb-4 p-4">
      <div class="row">
        <div class="col-sm-2 d-none d-sm-none">
          <div class="profilePicSmall mt-0 ml-0 mr-2 mb-2 profilePicOnlineSm">
            <img :src="profilePic" alt="" class="img-fluid" />
          </div>
        </div>
        <div id="belowCreatePost" class="col-12 col-sm-10">
          <textarea id="createPost"  v-model="text_content" :placeholder="writeSomethingTranslated"
          class="form-control" rows="1" @input="handleInput"></textarea>
          <br>
          <div class="row">
    <div class="col-6 col-sm-12 col-md-8">
        <a v-tooltip="imageUploadTranslated" class="text-333" href="javascript:void(0)" @click="selectPhotos">
            <img alt="photo icon" class="post-uploader-icons photo-icon" src="/svg/Upload-Picture.svg">
        </a>

        <a v-tooltip="videoUploadTranslated" class="text-333" href="javascript:void(0)" @click="selectMedia('Video')">
            <img alt="video icon" class="post-uploader-icons video-icon" src="/svg/video-new-icon.svg">
        </a>

        <a href="javascript:void(0)" @click="selectMedia('Audio')">
            <img v-tooltip="audioUploadTranslated" alt="audio icon" class="post-uploader-icons audio-icon" src="/svg/portable-speaker2.svg">
        </a>

        <a href="javascript:void(0)" @click="selectMedia('ZIP')"> 
            <img v-tooltip="zipUploadTranslated" alt="zip icon" class="post-uploader-icons zip-icon" src="/svg/zip-upload.svg">
        </a>

        <a v-if="postLockType === 'Paid'" ref="paidPostLockIcon" class="text-333" href="javascript:void(0)" @click="changePostLockType">
            <img alt="locked icon" class="post-uploader-icons lock-closed-icon" src="/svg/Locked.svg">
            {{ paidPostTranslated }}
        </a>

        <a v-if="postLockType === 'Free'" ref="freePostLockIcon" class="text-333" href="javascript:void(0)" @click="changePostLockType">
            <img alt="unlocked icon" class="post-uploader-icons lock-open-icon" src="/svg/Unlocked.svg">
            {{ freePostTranslated }}
        </a>
        <button class="btn mt-2"  data-toggle="tooltip" data-placement="top" :title="postScheduleTranslated">
          Livestream
        </button> 
        <input ref="imageUploads" accept="image/*" class="d-none" multiple type="file" @change="appendPhotos">
        <input ref="videoUploads" accept="video/mp4,video/webm,video/ogg,video/quicktime" class="d-none" type="file" @change="appendMedia">
        <input ref="audioUploads" accept="audio/mp3,audio/ogg,audio/wav" class="d-none" type="file" @change="appendMedia">
        <input ref="zipUploads" accept="zip,application/zip,application/x-zip,application/x-zip-compressed,.zip" class="d-none" type="file" @change="appendMedia">
      </div>
    <div class="col-6 col-sm-6 col-md-4 text-right">
      <a :class="['btnBlue', 'mr-0', 'mb-2', 'mt-3', 'mt-sm-1', 'mt-md-0', 'mt-sm-3']"
                 href="javascript:void(0)"
                 :style="{ opacity: isPublishButtonEnabled ? 1 : 0.6, cursor: isPublishButtonEnabled ? 'pointer' : 'not-allowed' }"
                 @click.prevent="isPublishButtonEnabled ? savePost() : null">
                <i class="far fa-paper-plane mr-1"></i> {{ savePostTranslated }}
              </a>
              <button class="btn" @click="toggledateinput" data-toggle="tooltip" data-placement="top" :title="postScheduleTranslated">
      <img alt="activity icon" class="post-uploader-icons photo-icon" src="/svg/schedule-activity.svg">
    </button>
<input v-if="showdateinput" type="datetime-local" v-model="schedule_post" id="scheduleInput" class="mr-0 mb-2 mt-3 mt-sm-1 mt-md-0 mt-sm-3">
   
</div>
</div>


        </div>

      </div><!-- .row -->
      <div class="row">
        <div v-for="(p, pIndex) in selectedPhotos" :key="'photoIndex'+pIndex" class="col-6 col-md-3 text-center">
          <img :src="p.url" alt="" class="img-fluid rounded"/><br>
          <a class="text-danger d-inline-block" href="javascript:void(0)" @click="removePhoto(pIndex)"><i
              class="fas fa-trash"></i></a>
        </div>

        <div v-if="file">
          {{ file.name }} - <a class="text-danger d-inline-block" href="javascript:void(0)" @click="removeFile"><i
            class="fas fa-trash"></i></a>
        </div>
      </div>

      <div id="progress-photos" class="progress-wrapper mt-5 mb-3" :class="{'d-none': uploadPercentage === 0}">
        <div class="progress-info">
          <div class="progress-percentage text-center">
            <span class="percent text-primary">{{uploadPercentage}}%</span>
          </div>
        </div>
        <div class="progress progress-xs">
          <div aria-valuemax="100" aria-valuemin="0" :aria-valuenow="uploadPercentage" :style="'width:' + uploadPercentage + '%;'" class="progress-bar progress-bar-striped"
               role="progressbar"></div>
        </div>
      </div>

      <div id="progress-others" class="progress-wrapper mt-5 mb-3" :class="{'d-none': progress === 0 || progress > 100}">
        <div class="progress-info">
          <div class="progress-percentage text-center">
            <span class="percent text-primary">{{progress}}%</span>
          </div>
        </div>
        <div class="progress progress-xs">
          <div aria-valuemax="100" aria-valuemin="0" :aria-valuenow="progress" :style="'width:' + progress + '%;'" class="progress-bar progress-bar-striped"
               role="progressbar"></div>
        </div>
      </div>

    </div><!-- ./card -->

    <div class="card text-center mb-4 p-3" v-if="loadPostSpinner || showSpinner">
      {{ processingUploadTranslated }}
      <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated w-100" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
    </div>

    <div ref="vueAppendedPosts"></div>
  </div>
</template>

<script>

const axios = require('axios');

export default {
  data() {
    return {
      writeSomethingTranslated: window.writeSomething,
      savePostTranslated: window.savePost,
      freePostTranslated: window.freePost,
      paidPostTranslated: window.paidPost,
      imageUploadTranslated: window.imageUpload,
      videoUploadTranslated: window.videoUpload,
      audioUploadTranslated: window.audioUpload,
      zipUploadTranslated: window.zipUpload,
      postScheduleTranslated: window.postSchedule,
      processingUploadTranslated: window.processingUpload,
      profilePic: "/images/default-profile-pic.png",
      authUser: null,
      postLockType: 'Paid',
      text_content: "",
      schedule_post: "",
      showSpinner: false,
      post: null,
      selectedPhotos: [],
      uploadPercentage: 0,
      attachmentType: null,
      file: null,
      chunks: [],
      uploaded: 0,
      loadPostSpinner: false,
      showdateinput:false
    }
  },
  
  mounted() {
    this.getUser();
    this.getProfilePic();
    // window.toggleInput = () => {
    //   var inputField = document.getElementById("scheduleInput");
    //   inputField.style.display = (inputField.style.display === "none" || inputField.style.display === "") ? "block" : "none";
    // };
  },
  methods: {
    handleInput(event) {
      const textarea = event.target;
      const startPos = textarea.selectionStart;
      const endPos = textarea.selectionEnd;
      let transformedText = textarea.value;

      // Check if there's been an addition or deletion
      if (transformedText.length > this.previousText.length) {
        // Text added
        transformedText = transformedText.replace(/(^|\s)(#[a-zA-Z0-9]+)/g, '$1<span style="color: blue;">$2</span>');
      } else if (transformedText.length < this.previousText.length) {
        // Text deleted
        transformedText = transformedText.replace(/(^|\s)(#[a-zA-Z0-9]+)/g, '$1<span style="color: blue;">$2</span>');
      }

      // Update textarea value with transformed text
      textarea.value = transformedText;

      // Restore cursor position
      const newCursorPos = startPos + (transformedText.length - this.previousText.length);
      textarea.setSelectionRange(newCursorPos, newCursorPos);

      // Update data model and previous text
      this.text_content = textarea.value;
      this.previousText = transformedText;
    },


    toggledateinput(){
      if (this.showdateinput) {
        
        this.schedule_post = null; 
       
    }
      // alert('ok');
      this.showdateinput = !this.showdateinput;
    },
    convertHashtags(content) {
      // Replace hashtags with anchor tags
      return content.replace(/#(\w+)/g, '<a href="#$1">#$1</a>');
    },
    getProfilePic() {
      const vm = this;
      axios.get('/profile/get/profilePicture')
          .then(function (response) {
            vm.profilePic = response.data.picture;
          })
          .catch(function (error) {
            vm.$toast.error('Fetching Profile Pic: ' + error.toString());
          });
    },
    getUser() {
      const vm = this;
      axios.post('/messages/get/auth-user')
          .then(function (response) {
            vm.authUser = response.data;
          })
          .catch(function (error) {
            vm.$toast.error('Fetching User: ' + error.toString());
          });
    },
    changePostLockType() {
      if (this.postLockType === 'Paid') {
        this.postLockType = 'Free';
      } else if (this.postLockType === 'Free') {
        this.postLockType = 'Paid';
      }
    },
    selectPhotos() {
      // click the input
      this.$refs.imageUploads.click();
    },
    appendPhotos(event) {
      const filesNum = event.target.files.length;
      const attachments = event.target.files;
      // append this file
      for (let i = 0; i < filesNum; i++) {
        const file = attachments[i];
        this.selectedPhotos.push({'file': file, 'url': URL.createObjectURL(file)});
      }
    },
    removePhoto(index) {
      console.log('Removing Photo By Index: ' + index);
      this.selectedPhotos.splice(index, 1);
    },
    savePost() {
      const vm = this;
      // show spinner
      vm.showSpinner = true;
      // send the request
      console.log('saveing')
      axios.post('/save-post', {
          'text_content': vm.text_content,
          'schedule_post': vm.schedule_post,
          // alert(vm.schedule_post);
          'lock_type': vm.postLockType
      }).then(function (response) {
        // alert(vm.schedule_post)
          if (response.data.result) {
              // set post id
              vm.post = response.data.post;
              vm.text_content = "";
              vm.schedule_post = "";
           
              // upload photos if required
              if (vm.selectedPhotos.length > 0) {
                console.log('uploadPhotos')
                  vm.uploadPhotos(vm.post);
              } else if (vm.file) {
                console.log('createChunks')
                  vm.createChunks();
              } else {
                console.log('loadPost')
                  vm.loadPost(vm.post);
              }
          } else {
            console.log('else')
              response.data.errors.forEach(function (err) {
                  vm.$toast.error(err);
              });
          }
          
          vm.showSpinner = false;
      }).catch(function (error) {
          let response = error.response.data;
          if (typeof response.errors != 'undefined') {
              for (let err in response.errors) {
                  if (response.errors.hasOwnProperty(err)) {
                      vm.$toast.error(response.errors[err][0]);
                  }
              }
          } else {
              vm.$toast.error(error.toString());
          }
      })
    },
    // resetForm() {
    //   this.text_content = '';
    //   this.schedule_post = ''; // Clear the value of the schedule_post variable
    // },
    uploadPhotos(postID) {
      // ref to vue
      const vm = this;
      // show spinner
      vm.showSpinner = true;
      // compute the form data
      const postData = new FormData();
      // append image post type
      postData.append('media_type', 'Image');
      for (let fileKey in this.selectedPhotos) {
        postData.append('imageUpload[]', this.selectedPhotos[fileKey].file);
      }
      // send the request
      axios.post('/post/attach-media/' + postID,
          postData,{
          onUploadProgress: event => {
              vm.uploadPercentage = parseInt( Math.round( ( event.loaded / event.total ) * 100 ) );
          }
      }).then(function (response) {
            if (response.data.result) {
              // load post
              vm.loadPost(postID);
            } else {
              response.data.errors.forEach(function (err) {
                vm.$toast.error(err);
              });
            }
          }).catch(function (error) {
        let response = error.response.data;
        if (typeof response.errors != 'undefined') {
          for (let err in response.errors) {
            if (response.errors.hasOwnProperty(err)) {
              vm.$toast.error(response.errors[err][0]);
            }
          }
        } else {
          vm.$toast.error(error.toString());
        }
      }).then(function () {
        vm.showSpinner = false;
        vm.selectedPhotos = [];
        vm.uploadPercentage = 0;
      });
    },
    loadPost(postID) {
    const vm = this;
    vm.loadPostSpinner = true;

    axios.get('/post/loadById/' + postID)
        .then(function(response) {
            const currentHTML = vm.$refs.vueAppendedPosts.innerHTML;
            const modifiedHTML = vm.processHashTags(response.data); // Process hashtags
            vm.$refs.vueAppendedPosts.innerHTML = modifiedHTML + currentHTML;
        })
        .catch(function(error) {
            vm.$toast.error(error.toString());
        })
        .finally(function() {
            vm.loadPostSpinner = false;
        });
},

    processHashTags(html) {
        // Regular expression to match words starting with '#'
        const hashTagRegex = /#(\w+)/g;
        
        // Replace hashtags with links
        const processedHTML = html.replace(hashTagRegex, '<a href="/hashtags/$1">#$1</a>');
        
        return processedHTML;
    },
    appendMedia() {
      console.log('setting file');
      // set file
      this.file = event.target.files.item(0);
      console.log(this.file);
    },
    removeFile() {
      this.file = null;
      this.chunks = [];
    },
    selectMedia(mediaType) {
        // set media type
        this.attachmentType = mediaType;
        // trigger click
        if(mediaType === 'Video') {
          this.$refs.videoUploads.click();
        }else if(mediaType === 'Audio') {
          this.$refs.audioUploads.click();
        }else if(mediaType === 'ZIP') {
          this.$refs.zipUploads.click();
        }
    },
    createChunks() {
      // 8 mb chunks
      let size = 1024 * 1024 * 8, chunks = Math.ceil(this.file.size / size);
      for (let i = 0; i < chunks; i++) {
        this.chunks.push(this.file.slice(
            i * size, Math.min(i * size + size, this.file.size), this.file.type
        ));
      }
    },
    uploadChunks() {
      // ref to vue
      const vm = this;
      // show spinner
      vm.showSpinner = true;
      // show post loader
      vm.loadPostSpinner = true;
      // compute the form data
      const postData = new FormData();
      // append media_type request
      postData.append('media_type', vm.attachmentType);
      postData.append('is_last', vm.chunks.length === 1);
      postData.set('file', this.chunks[0], `${this.file.name}.part`);
      // send the request
      axios.post('/post/attach-media/' + vm.post,
          postData,{
            onUploadProgress: event => {
              vm.uploaded += event.loaded;
            }
          }).then(function (response) {
        if (response.data.result) {
          // load post
          if(vm.chunks.length <= 1) {
            vm.file = null;
            vm.chunks = [];
            vm.uploadPercentage = 0;
            vm.uploaded = 0;
            vm.attachmentType = null;
            vm.loadPost(vm.post);
          }
          vm.chunks.shift();
        } else {
          response.data.errors.forEach(function (err) {
            vm.$toast.error(err);
          });
        }
      }).catch(function (error) {
        // reset file if error
        vm.file = null;
        vm.chunks = [];
        vm.uploadPercentage = 0;
        vm.uploaded = 0;
        let response = error.response.data;
        if (typeof response.errors != 'undefined') {
          for (let err in response.errors) {
            if (response.errors.hasOwnProperty(err)) {
              vm.$toast.error(response.errors[err][0]);
            }
          }
        } else {
          vm.$toast.error(error.toString());
        }
      }).then(function () {
        vm.showSpinner = false;
      });
    },
  },
  watch: {
    chunks(n, o) {
      if (n.length > 0) {
        this.uploadChunks();
      }
    }
  },
  computed: {
    transformedContent() {
      const transformedText = this.text_content.replace(/#(\w+)/g, '<span style="color: blue;">#$1</span>');
      return transformedText;
    },

    isPublishButtonEnabled() {
      return this.text_content.trim() !== '' || this.selectedPhotos.length > 0 || this.file !== null;
    },
    progress() {
      if(this.file) {
        return Math.floor((this.uploaded * 100) / this.file.size);
      }else{
        return 0;
      }
    }
  }
}

</script>

<style>
.tooltip-vue {
  display: block !important;
  z-index: 10000;
}

.tooltip-vue-inner {
  background: black;
  color: white;
  border-radius: 16px;
  padding: 5px 10px 4px;
}

.tooltip-vue-arrow {
  width: 0;
  height: 0;
  border-style: solid;
  position: absolute;
  margin: 5px;
  border-color: black;
  z-index: 1;
}

.tooltip-vue[x-placement^="top"] {
  margin-bottom: 5px;

.tooltip-vue-arrow {
  border-width: 5px 5px 0 5px;
  border-left-color: transparent !important;
  border-right-color: transparent !important;
  border-bottom-color: transparent !important;
  bottom: -5px;
  left: calc(50% - 5px);
  margin-top: 0;
  margin-bottom: 0;
}

}


.tooltip-vue[aria-hidden='true'] {
  visibility: hidden;
  opacity: 0;
  transition: opacity .15s, visibility .15s;
}

.tooltip-vue[aria-hidden='false'] {
  visibility: visible;
  opacity: 1;
  transition: opacity .15s;
}

.form-control {
  resize: none; /* Prevent textarea from being resized */
}
</style>