export const formatCurrency = {
 methods: {
  formatCurrency(data) {
   return (parseFloat(data)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  },
 }
}

export const toast = {
 data: () => ({
  initialLoading: false
 }),
 methods: {
  toastData(status, data) {
   if (status == 200) {
    this.$toast.success(data.msg)
   }
   else {
    if(status == 401){
     this.$toast.error(data.error)
    }
    else {
     this.$toast.error('Something went wrong!')
    }
   }
  }
 }
}

export const birthdate = {
 data: () => ({
  maxBirthDate: ''
 }),
 methods: {
  maxDate() {
   const date = new Date();
   date.get
   const newDate = (date.getFullYear() - 18).toString() + '-' + (date.getMonth() + 1).toString() + '-' + date.getDate().toString();
   this.maxBirthDate = newDate;
  },
 }
}