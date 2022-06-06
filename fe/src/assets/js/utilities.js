export const formatCurrency = {
 methods: {
  formatCurrency(data) {
   // const formattedData = (parseFloat(data)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
   // return isNaN(formattedData) ?  '0.00' : formattedData
   var formatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
    currencyDisplay: "code"
   });

   return formatter.format(data); /* $2,500.00 */
  },
 }
}

export const formatNumeric = {
 methods: {
  formatNumeric(data) {
   return data < 10 ? '0' + data : data
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
    if (status == 422) {
     if (data.msg) {
      this.$toast.error(data.msg)
     }
     else {
      this.$toast.error('Something went wrong!')

     }
    } else {
     if (status == 401) {
      this.$toast.error(data.error)
     }
     else {
      this.$toast.error('Something went wrong!')
     }
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