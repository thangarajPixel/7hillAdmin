<script>
    // global app configuration object
    const config = {
       routes: {
           'roles': {
                'roles': "{{ route('roles') }}",
                'delete': "{{ route('roles.delete') }}",
                'status': "{{ route('roles.status') }}",
                'add': "{{ route('roles.add.edit') }}",
                'export': {
                    'excel': "{{ route('roles.export.excel') }}",
                    'pdf': "{{ route('roles.export.pdf') }}",
                }
           },
           'users': {
               
               'delete': "{{ route('users.delete') }}",
               'status': "{{ route('users.status') }}",
               'add': "{{ route('users.add.edit') }}",
               'export': {
                   'excel': "{{ route('users.export.excel') }}",
                   'pdf': "{{ route('users.export.pdf') }}",
               }
            },
            'main_category': {
               
               'delete': "{{ route('main_category.delete') }}",
               'status': "{{ route('main_category.status') }}",
               'add': "{{ route('main_category.add.edit') }}",
               'export': {
                   'excel': "{{ route('main_category.export.excel') }}",
                   'pdf': "{{ route('main_category.export.pdf') }}",
               }
            },          
            'sub_category': {
               
               'delete': "{{ route('sub_category.delete') }}",
               'status': "{{ route('sub_category.status') }}",
               'add': "{{ route('sub_category.add.edit') }}",
               'export': {
                   'excel': "{{ route('sub_category.export.excel') }}",
                   'pdf': "{{ route('sub_category.export.pdf') }}",
               }
            },
           
            'products': {
               'delete': "{{ route('products.delete') }}",
               'status': "{{ route('products.status') }}",
               'add': "{{ route('products.add.edit') }}",
               'export': {
                   'excel': "{{ route('products.export.excel') }}",
                   'pdf': "{{ route('products.export.pdf') }}",
               }
            },
            'product-category': {
               'delete': "{{ route('product-category.delete') }}",
               'status': "{{ route('product-category.status') }}",
               'add': "{{ route('product-category.add.edit') }}",
               'export': {
                   'excel': "{{ route('product-category.export.excel') }}",
                   'pdf': "{{ route('product-category.export.pdf') }}",
                }
            },
            'customer': {
               'delete': "{{ route('customer.delete') }}",
               'status': "{{ route('customer.status') }}",
               'add': "{{ route('customer.add.edit') }}",
               'export': {
                   'excel': "{{ route('customer.export.excel') }}",
                   'pdf': "{{ route('customer.export.pdf') }}",
                }
               
            },   
            'banner': {
               'delete': "{{ route('banner.delete') }}",
               'status': "{{ route('banner.status') }}",
               'add': "{{ route('banner.add.edit') }}",
               'export': {
                   'excel': "{{ route('banner.export.excel') }}",
                   'pdf': "{{ route('banner.export.pdf') }}",
                }
            },  
            'industrial-module': {
               'delete': "{{ route('industrial-module.delete') }}",
               'status': "{{ route('industrial-module.status') }}",
               'add': "{{ route('industrial-module.add.edit') }}",
               'export': {
                   'excel': "{{ route('industrial-module.export.excel') }}",
                   'pdf': "{{ route('industrial-module.export.pdf') }}",
                }
            }, 
           
       }
   };
</script>