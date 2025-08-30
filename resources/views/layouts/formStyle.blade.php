<style>
    .bg-form{
        background-color:  #e9ecef;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: #ffffff;
        opacity: 1;
    }
    .form-control-sm {
        height: calc(1.5125rem + 2px);
        padding: .15rem .5rem;
        font-size: .750rem;
        line-height: 1.5;
        border-radius: .2rem;
        background-color: #ffffff !important;
    }
    .btn-sm{
        font-size: 10px !important;
        height: 25px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .bb{
        border-bottom: 1px solid rgb(145, 138, 138);
    }

    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }

    .centered{
        vertical-align: middle !important;
    }

    .product-list {
        animation: fadeIn 0.4s ease;    
    }

    .product-form {
        animation: fadeIn 0.4s ease;
    }

    @media (min-width: 992px) {
        .col-lg-12.product-list {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .col-lg-9.product-list {
            flex: 0 0 75%;
            max-width: 75%;
        }
    }

    /* Optional: Fade-in animation for product-form */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Optional: button styling */
    #toggleForm {
        transition: all 0.3s ease;
    }

    #toggleForm:hover {
        background-color: #0d6efd;
        color: #fff;
    }
</style>