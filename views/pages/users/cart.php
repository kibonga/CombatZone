<div class="container">
    <div>
        <p class="lead">Items in cart: <span id="numItemsCart"></span></p>
    </div>
    <table class="table table-striped table-bordered text-center">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Category</th>
                <th scope="col">Brand</th>
                <th scope="col">Color</th>
                <th scope="col">Size</th>
                <th scope="col">Price</th>
                <th scope="col">Quantity</th>
                <th scope="col">Total</th>
                <th scope="col">Remove</th>
            </tr>
        </thead>
        <tbody id="cart" class="table-striped">

        </tbody>
    </table>
</div>
<section class="section-content padding-y">
    <div class="container">

        <div class="row">
            <main class="col-md-9">
                <div class="card">
                    <div class="card-body border-top">
                        <a href="#" class="btn bg-c-ternary text-white box-shadow float-md-right" id="purchase"> Make Purchase </a>
                        <a href="index.php?page=shop" class="btn box-shadow"> <i class="fa fa-chevron-left"></i> Continue shopping </a>
                        <br>
                        <span class="lead bg-c-primary text-white mt-3 box-shadow rounded mt-2 d-inline-block" id="purchased"></span>
                    </div>
                    <p class="lead text-success ms-3" id="cart-success"></p>
                </div> <!-- card.// -->

                <div class="alert text-white bg-c-secondary mt-3 d-flex">
                    <span class="material-icons">
                        local_shipping
                    </span>
                    <p class="icontext ms-2">Free Delivery within 1-2 weeks</p>
                </div>

            </main> <!-- col.// -->
            <aside class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h3 class="lead">Order</h3>
                        </div>
                        <dl class="dlist-align">
                            <dt>Price:</dt>
                            <dd class="text-right">&dollar;<span id='price'></span></dd>
                        </dl>
                        <hr>
                        <dl class="dlist-align">
                            <dt>Total:</dt>
                            <dd class="text-right  h5">&dollar;<strong id="totalPrice"></strong></dd>
                        </dl>
                    </div> <!-- card .// -->
            </aside> <!-- col.// -->
        </div>

    </div> <!-- container .//  -->
</section>
