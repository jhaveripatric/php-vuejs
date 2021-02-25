var app = new Vue({

  el: "#app",
  data: {
    selected: '',
    showingaddModal : false,
    showingeditModal : false,
    showingdeleteModal : false,
    errorMessage: "",
    successMessage: "",
    catSelect: '',
    catOptions:[{id:'1',category:'cat name',created_at:'',updated_at:''}],
    documents: [],
    newDoc: {},
    clickedDoc: {},
  },

  mounted: function () {
  	this.getCategories();
  },

  methods: {
    doMath:function(index){
      return index+ 1 ;
    },
  	getCategories: function () {
  		axios.get('api/api.php?action=getCategories')
        .then(function (res) {
            app.catOptions = res.data.category;

        })
        .catch(function (error) {
            console.log(error);
        });
  	},

    getDocuments(event) {
      $('.doctable').css('display','block');
      var cat_id = event.target.value;
      axios.get('api/api.php?action=getDocuments&cat_id='+cat_id)
        .then(function (res) {
            app.selected = event;
            app.documents = res.data.document;
        })
        .catch(function (error) {
            console.log(error);
        });
      // console.log(event.target.value);
    },

    addDocument: function() {
      var cat_id = app.selected.target.value;
      let formData = app.toFormData(this.newDoc);
      formData.append('catid', cat_id);
      axios.post('api/api.php?action=addDocument',formData)
        .then(function (res) {
          app.newDoc = {};
            if (res.data.error) {
              app.errorMessage = res.data.message;
            } else {
              app.successMessage = res.data.message;
              app.getDocuments(app.selected);
            }

        }).catch(function (error) {
            console.log(error);
        });
    },

    updateDoc: function() {
      let formData = app.toFormData(app.clickedDoc);
      axios.post('api/api.php?action=updateDoc',formData)
        .then(function (res) {
          app.clickedDoc = {};
          if (res.data.error) {
            app.errorMessage = res.data.message;
          } else {
            app.successMessage = res.data.message;
            app.getDocuments(app.selected);
          }
        }).catch(function (error) {
            console.log(error);
        });
    },

    deleteDoc: function() {
      let formData = app.toFormData(app.clickedDoc);
      axios.post('api/api.php?action=deleteDoc',formData)
        .then(function (res) {
          app.clickedDoc = {};
          if (res.data.error) {
            app.errorMessage = res.data.message;
          } else {
            app.successMessage = res.data.message;
            app.getDocuments(app.selected);
          }
        }).catch(function (error) {
            console.log(error);
        });
    },

    selectDoc(doc) {
      app.clickedDoc = doc;
    },

    toFormData: function (obj) {
      var form_data = new FormData();
      for (var key in obj) {
        form_data.append(key, obj[key]);
      }
      return form_data;
    },

  }
});