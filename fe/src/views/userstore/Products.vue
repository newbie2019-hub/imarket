<template>
  <div class="mb-12">
    <p class="font-2x font-weight-bold ml-2 mt-5 mb-0">Product Management</p>
    <p class="grey--text ml-2">You can manage your products on the table below</p>
    <v-layout d-flex justify-end align-center class="mb-10">
      <v-btn color="green darken-2" @click.prevent="dialogNewProduct = true" depressed dark>New Product</v-btn>
      <v-col lg="4" md="5" sm="5">
        <v-text-field dense hide-details="auto" outlined prepend-inner-icon="mdi-magnify" color="orange darken-2" label="Search Product" v-model="search"></v-text-field>
      </v-col>
    </v-layout>
    <v-data-table :search="search" :headers="headers" :items="products" :items-per-page="5" class="elevation-0">
      <template v-slot:item.product_info.image="{ item }">
        <v-img class="mt-2 mb-2" max-width="80" :src="`http://127.0.0.1:8000/images/products/${item.product_info.image}`"></v-img>
      </template>
      <template v-slot:item.product_info.name="{ item }">
        <p class="text-no-wrap">{{ item.product_info.name }}</p>
      </template>
      <template v-slot:item.product_info.price="{ item }">
        <p class="text-no-wrap">{{ formatCurrency(item.product_info.price) }}</p>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-layout d-flex>
          <v-btn @click.prevent="setProduct(item)" color="primary" depressed plain>Update</v-btn>
          <v-btn
            @click="
              setDeleteProduct(item)
            "
            color="red"
            depressed
            plain
            >Delete</v-btn
          >
        </v-layout>
      </template>
    </v-data-table>
    <v-dialog v-model="dialogNewProduct" max-width="530">
      <v-card class="pt-5">
        <v-form @submit.prevent="saveProduct" class="pl-5 pr-5" lazy-validation v-model="validProduct" ref="newProduct">
          <h4 class="font-weight-bold text-h5">Add Product</h4>
          <h4 class="font-weight-light mb-5">All fields are required</h4>
          <v-file-input
            :rules="required"
            @change="uploadImage"
            accept="image/png, image/jpeg, image/bmp"
            outlined
            prepend-icon=""
            prepend-inner-icon="mdi-file-image"
            show-size
            counter
            label="Product Image"
          ></v-file-input>
          <v-text-field hide-details="auto" prepend-inner-icon="mdi-package" v-model="data.name" :rules="required" label="Name" required outlined></v-text-field>
          <v-text-field
            class="mt-3"
            hide-details="auto"
            prepend-inner-icon="mdi-card-bulleted-outline"
            v-model="data.description"
            :rules="required"
            label="Description"
            required
            outlined
          ></v-text-field>
          <v-layout d-flex>
            <v-text-field type="number" class="mt-3" hide-details="auto" prepend-inner-icon="mdi-cash" v-model="data.price" :rules="required" label="Price" required outlined></v-text-field>
            <v-text-field type="number" class="mt-3" hide-details="auto" prepend-inner-icon="mdi-numeric" v-model="data.quantity" :rules="required" label="Quantity" required outlined></v-text-field>
          </v-layout>
          <v-select :rules="required" class="mt-3" outlined v-model="data.category" item-value="id" item-text="category" :items="categories" label="Select Category"></v-select>
          <v-card-actions class="pb-3 pt-5">
            <v-spacer></v-spacer>
            <v-btn plain color="grey darken-1" text @click="dialogNewProduct = false" :loading="btnLoading"> Cancel </v-btn>
            <v-btn type="submit" color="green darken-1" text :loading="btnLoading"> Save Product </v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogUpdateProduct" max-width="530">
      <v-card class="pt-5">
        <v-form @submit.prevent="updateProduct" class="pl-5 pr-5" lazy-validation v-model="validProduct" ref="updateProduct">
          <h4 class="font-weight-bold text-h5">Update Product</h4>
          <h4 class="font-weight-light mb-5">All fields are required</h4>
          <v-file-input
            @change="uploadImage"
            accept="image/png, image/jpeg, image/bmp"
            outlined
            prepend-icon=""
            prepend-inner-icon="mdi-file-image"
            show-size
            counter
            label="Product Image"
          ></v-file-input>
          <v-text-field hide-details="auto" prepend-inner-icon="mdi-package" v-model="product.name" :rules="required" label="Name" required outlined></v-text-field>
          <v-text-field
            class="mt-3"
            hide-details="auto"
            prepend-inner-icon="mdi-card-bulleted-outline"
            v-model="product.description"
            :rules="required"
            label="Description"
            required
            outlined
          ></v-text-field>
          <v-layout d-flex>
            <v-text-field type="number" class="mt-3" hide-details="auto" prepend-inner-icon="mdi-cash" v-model="product.price" :rules="required" label="Price" required outlined></v-text-field>
            <v-text-field
              type="number"
              class="mt-3"
              hide-details="auto"
              prepend-inner-icon="mdi-numeric"
              v-model="product.quantity"
              :rules="required"
              label="Quantity"
              required
              outlined
            ></v-text-field>
          </v-layout>
          <v-select :rules="required" class="mt-3" outlined v-model="product.category" item-value="id" item-text="category" :items="categories" label="Select Category"></v-select>
          <v-card-actions class="pb-4 pt-5">
            <v-spacer></v-spacer>
            <v-btn plain color="grey darken-1" text @click="dialogUpdateProduct = false" :loading="btnLoading"> Cancel </v-btn>
            <v-btn type="submit" color="green darken-1" text :loading="btnLoading"> Save Update </v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogDeleteProduct" max-width="480">
      <v-card>
        <v-card-title class="text-h5"> Confirm Delete </v-card-title>
        <h4 class="font-weight-light ml-6 mr-5 mb-5">Are you sure you want to delete this product?</h4>
        <v-card-actions class="pb-3">
          <v-spacer></v-spacer>
          <v-btn color="grey" plain @click="dialogDeleteProduct = false" :loading="btnLoading"> Cancel </v-btn>
          <v-btn color="red darken-1" plain @click="deleteProduct" :loading="btnLoading"> Delete </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  import { formatCurrency } from '@/assets/js/utilities';
  import { rules } from '@/assets/js/rules';
  import API from '../../store/base/base';
  export default {
    mixins: [formatCurrency, rules],
    data: () => ({
      headers: [
        { text: 'IMG', value: 'product_info.image' },
        { text: 'Product', value: 'product_info.name' },
        { text: 'Description', value: 'product_info.description' },
        { text: 'Category', value: 'product_info.category.category' },
        { text: 'Price', value: 'product_info.price' },
        { text: 'Qty', value: 'product_info.quantity' },
        { text: 'Action', value: 'actions' },
      ],
      search: '',
      validProduct: true,
      dialogNewProduct: false,
      dialogUpdateProduct: false,
      dialogDeleteProduct: false,
      productID: null,
      btnLoading: false,
      initialLoading: false,
      product: {
        name: '',
        category: '',
        description: '',
        price: '',
        quantity: '',
      },
      data: {
        name: '',
        category: '',
        description: '',
        price: '',
        quantity: '',
      },
    }),
    async mounted() {
      this.initialLoading = true;
      await this.$store.dispatch('products/getProducts');
      await this.$store.dispatch('market/getCategories');
      this.initialLoading = false;
    },
    methods: {
      setProduct(data) {
        this.product.id = data.id;
        this.product.name = data.product_info.name;
        this.product.description = data.product_info.description;
        this.product.price = data.product_info.price;
        this.product.quantity = data.product_info.quantity;
        this.product.category = data.product_info.category_id;

        this.dialogUpdateProduct = true;
      },
      setDeleteProduct(data){
        console.log(data)
        this.productID = data.id
        this.dialogDeleteProduct = true
      },
      async deleteProduct() {
        this.btnLoading = true;
        const { status, data } = await this.$store.dispatch('products/deleteProduct', this.productID);
        this.toastData(status, data);
        await this.$store.dispatch('products/getProducts');
        this.dialogDeleteProduct = false;
        this.btnLoading = false;
      },
      async updateProduct() {
        const valid = this.$refs.updateProduct.validate();
        if (valid) {
          this.btnLoading = true;
          const { status, data } = await this.$store.dispatch('products/updateProduct', this.product);
          this.toastData(status, data);
          await this.$store.dispatch('products/getProducts');
          this.dialogUpdateProduct = false;
          this.$refs.updateProduct.reset();
          this.btnLoading = false;
        } else {
          this.$toast.error('Please fill-in all fields');
        }
      },
      async saveProduct() {
        const valid = this.$refs.newProduct.validate();
        if (valid) {
          this.btnLoading = true;
          const { status, data } = await this.$store.dispatch('products/saveProduct', this.data);
          this.toastData(status, data);
          await this.$store.dispatch('products/getProducts');
          this.dialogNewProduct = false;
          this.$refs.newProduct.reset();
          this.btnLoading = false;
        } else {
          this.$toast.error('Please fill-in all fields');
        }
      },
      async uploadImage(event) {
        console.log(event);
        if (event) {
          let formData = new FormData();
          formData.append('image', event);
          await API.post(`products/uploadImage`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
            .then((response) => {
              this.data.image = response.data;
              this.product.image = response.data;
            })
            .catch((error) => {
              console.log({ error });
            });
        }
      },
    },
    computed: {
      ...mapState('products', ['products']),
      ...mapState('market', ['categories']),
    },
  };
</script>
<style>
  .v-slide-group__prev .v-slide-group__prev--disabled {
    display: none !important;
  }
</style>
