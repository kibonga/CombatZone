$(document).ready(() => {
  // Products class
  class Data {
    // Returns a Order Line object whic will be inserted into order
    static returnOrderLine() {
      const raSizes = document.querySelectorAll("input[name=raSizes]");
      let raSize = null;
      raSizes.forEach((rs) => {
        console.log(rs);
        if (rs.checked) {
          raSize = rs.value;
        }
      });
      // Prepare data object
      const sizesNames = document.querySelector("#sizes").value.split(",");
      const sizesID = document.querySelector("#sizesID").value.split(",");
      console.log(sizesNames);
      console.log(sizesID);

      let sizeName = null;
      sizesID.forEach((id, i) => {
        if (id == raSize) {
          sizeName = sizesNames[i];
        }
      });

      const data = {
        inGloveName: document.querySelector("#name").value,
        inGloveID: document.querySelector("#id").value,
        inCatName: document.querySelector("#cat").value,
        inCatID: document.querySelector("#catID").value,
        inBrandName: document.querySelector("#brand").value,
        inBrandID: document.querySelector("#brandID").value,
        inColor: document.querySelector("#color").value,
        inColorID: document.querySelector("#colorID").value,
        // inSizesNames: document.querySelector("#sizes").value,
        // inSizesID: document.querySelector("#sizesID").value,
        inMeasure: document.querySelector("#measure").value,
        inPrice: document.querySelector("#price").value,
        inImg: document.querySelector("#t_img").value,
        inQuantity: document.querySelector("#inQuantity").value,
        raSize: raSize,
        raSizeName: sizeName,
        inUserID: document.querySelector("#inUserID").value,
      };

      return data;
    }

    // Return
    static returnOrderDetail() {
      const order = Storage.returnOrder();
      const data = new FormData();
      let arr = [];
      order.forEach((o, i) => {
        arr.push({
          id_user: o.inUserID,
          id_size: o.raSize,
          id_glove: o.inGloveID,
          price_purchase: o.inPrice,
          quantity: o.inQuantity,
          name_glove: o.inGloveName,
          name_size: o.raSizeName,
          measure: o.inMeasure,
        });
      });
      console.log(JSON.stringify(arr));
      arr = JSON.stringify(arr);
      data.append("order", arr);
      // data.append("order",  JSON.stringify(Storage.returnOrder));
      return data;
    }
  }

  class Storage {
    // Get Gloves order
    static returnOrder() {
      let order = null;
      if (localStorage.getItem("order") === null) {
        order = [];
      } else {
        order = JSON.parse(localStorage.getItem("order"));
      }
      return order;
    }

    // Add new order line to order
    static addOrderLine(orderLine, type = "add") {
      // Gets the current order
      console.log("How many times");
      const order = this.returnOrder();
      const ol = this.inStorage(orderLine, type);

      // Checks if order line (ol) exists in order
      if (ol) {
        // Order line already exists
        const i = ol.index;
        order[i] = ol;
      } else {
        // Order line does not exist
        // We used orderLine instead of ol because inStorage() would return either ol object or false
        order.push(orderLine);
      }
      localStorage.setItem("order", JSON.stringify(order));
      // Display number of gloves in cart
      // Helper.cartItemsNumber();
    }

    // Checks if order line is already in storage
    static inStorage(ol, type) {
      // Will return array so it's never null, but can be empty
      const order = this.returnOrder();

      for (let i in order) {
        if (
          order[i].inUserID == ol.inUserID &&
          order[i].raSize == ol.raSize &&
          order[i].inGloveID == ol.inGloveID
        ) {
          // User id, size id and glove id are all equal
          if (type == "add") {
            console.log("inStorage");
            console.log(ol);
            console.log(ol.inQuantity);
            order[i].inQuantity =
              Number(order[i].inQuantity) + Number(ol.inQuantity);
          } else if (type == "replace") {
            order[i].inQuantity = Number(ol.inQuantity);
          }
          order[i].index = i;

          return order[i];
        }
      }
      return false;
    }

    // Remove order line from order
    static removeOrderLine(userID, gloveID, sizeID) {
      const order = this.returnOrder();

      for (let i in order) {
        if (
          Number(order[i].inUserID) == Number(userID) &&
          Number(order[i].inGloveID) == Number(gloveID) &&
          Number(order[i].raSize) == Number(sizeID)
        ) {
          // All 3 conditions are checked userID, gloveID, sizeID
          // Creates shallow copy of the array
          order.splice(i, 1);
        }
      }
      // Create new localStorage array
      localStorage.setItem("order", JSON.stringify(order));
      // Initialize cart
      Helper.cartItemsNumber();
      Display.glovesCart();
    }

    // Remove order from localStorage
    static removeOrder() {
      localStorage.removeItem("order");
      // Initialize cart
    }

    // Removes user from localStorage
    static removeUser() {
      localStorage.removeItem("user");
    }

    // Creates order from cart
    static createOrderFromCart(cart) {
      console.log(cart);
      for (let i in cart) {
        this.addOrderLine(cart[i]);
      }
    }
  }

  class Helper {
    // Error Modal
    static errModal(status, arrMsg) {
      const errModal = new bootstrap.Modal(document.querySelector("#errModal"));
      document.querySelector("#errModalStatus").innerHTML = status;
      let content = ``;
      for (let i in arrMsg) {
        if (typeof arrMsg[i] !== "object") {
          content += `
            <p class="lead">${Number(i) + 1}. ${arrMsg[i]}</p>
          `;
        } else {
          for (let j in arrMsg[i]) {
            content += `
              <p class="lead">${Number(i) + 1}.${Number(j) + 1} ${
              arrMsg[i][j]
            }</p>`;
          }
        }
      }
      document.querySelector("#errModalMessage").innerHTML = content;
      return errModal;
    }
    // Success Modal
    //("cart", "Order", "You are about to make purchase. Are you sure?", "continue")
    static defaultModal(type, title, msg, btn = "Close") {
      console.log(type);
      console.log(title);
      console.log(msg);
      console.log(btn);
      const defModal = new bootstrap.Modal(
        document.querySelector("#defaultModal")
      );
      const arr = [title, msg, btn];
      const div = [
        "defaultModalTitle",
        "defaultModalMessage",
        "defaultModalBtn",
      ];
      arr.forEach((x, i) => {
        document.querySelector(`#${div[i]}`).innerHTML = x;
      });
      // document.querySelector("#defaultModalTitle").innerHTML = status;
      // document.querySelector("#defaultModalMessage").innerHTML = msg;
      return defModal;
    }
    // Returns array of checked checkboxes
    static getCheckboxes(name) {
      let array = [];
      $(`#${name} input:checked`).each(function () {
        array.push($(this).val());
        console.log(this);
      });
      return array.join(",");
    }
    // Display number of items in cart in navigation
    static cartItemsNumber() {
      // if (localStorage.getItem("user") === "regular_user") {
      const user = this.isCustomer();
      if (user) {
        const cart = document.querySelector("#cartNav");
        const order = Storage.returnOrder();
        cart.innerHTML = order.length;
        if (document.querySelector("#numItemsCart")) {
          document.querySelector("#numItemsCart").innerHTML = order.length;
        }
      }
    }
    // Checks if user has logged out, and if so, destroy localStorage user
    static isLogout() {
      if (document.querySelector("#btnLogout")) {
        document.querySelector("#btnLogout").addEventListener("click", (e) => {
          // localStorage.removeItem("user");
          e.preventDefault();
          if (this.isOrder()) {
            console.log("Yeah this is order");
            const order = Storage.returnOrder();
            // Listen up Motherfucker, we sending array of obejcts
            // Form Data only accepts, you guessed it, form elements
            // This won't work FormData(order)
            // We need to append it to form data shit
            // But that form data ain't no dumbass, it knows we not passing no form element
            // So we need a workaround, Stringify that bitch nigga
            const data = new FormData();
            data.append("order", JSON.stringify(order));

            Ajax.isFormData(
              "models/auth/Logout.php",
              "POST",
              data,
              (data) => {
                console.log(data);
                Storage.removeUser();
                Storage.removeOrder();
                Callback.redirect();
              },
              (err) => {
                console.log(err);
                const msg = err.responseJSON;
                const status = `Oops...${err.status} error occured`;
                // Helper.defaultModal("error-login", status, msg).show();
                Helper.errModal(status, msg).show();
              }
            );
          } else {
            // There is no order
            console.log("No it's not");
            Ajax.isFormData(
              "models/auth/Logout.php",
              "POST",
              {},
              (data) => {
                console.log(data);
                Storage.removeUser();
                Storage.removeOrder();
                Callback.redirect();
              },
              (err) => {
                // Helper.defaultModal();
                console.log(err);
                const msg = err.responseJSON;
                const status = `Oops...${err.status} error occured`;
                // Helper.defaultModal("error-login", status, msg).show();
                Helper.errModal(status, msg).show();
              }
            );
          }
        });
      }
    }

    // Checks if customer is logged in
    static isCustomer() {
      if (localStorage.getItem("user") === "regular_user") {
        return true;
      } else {
        return false;
      }
    }

    // Checks if user is logged in
    static isUser() {
      if (localStorage.getItem("user")) {
        return true;
      } else {
        return false;
      }
    }

    // Checks if there are Order lines in Order
    static isOrder() {
      const order = Storage.returnOrder();
      console.log(order);
      if (order.length > 0) {
        return true;
      } else {
        return false;
      }
    }

    // Check if current user is locked
    static isLock(isLock, id) {
      let content = ``;
      if (Number(isLock)) {
        content += `
          <div id="unlock-${id}">
            <a href="#" data-id="${id}" class="text-ternary remove-link locked-account">
              <span class="material-icons">
                lock
              </span>
            </a>
          </div>
        `;
      } else {
        content += `
          <span class="material-icons">
            check
          </span>
        `;
      }
      return content;
    }
  }

  class Callback {
    // Authorization/Redirect callbacks
    static redirect() {
      window.location = "index.php";
    }
  }

  class RegExp {
    static regPass =
      /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,30}$/;
    static regEmail = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    static regPhone = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
    static regAddress = /^[#.0-9a-zA-Z\s,-]{2,50}$/;
    static regName = /^[A-ZČĆŽŠĐ][a-zčćžšđ]{2,15}$/;
    static regGloveName = /^[\w ]{2,50}$/;
    static regPrice = /^\d{1,8}(?:\.\d{1,4})?$/;
    static regSubject = /(^[\w](( \w+)|(\w*))*$)|(^\w$)/;
  }

  class Ajax {
    static callback(url, method, data, callback, err) {
      $.ajax({
        url: url,
        method: method,
        data: data,
        dataType: "json",
        success: callback,
        error: err,
      });
    }
    // Callback for handling Form data
    // Form data only accepts form elements, so it needs to be stringified
    static isFormData(url, method, data, callback, err) {
      $.ajax({
        url: url,
        method: method,
        data: data,
        // Important we define these
        contentType: false,
        processData: false,
        cache: false,

        success: callback,
        error: err,
      });
    }
  }

  // Validator
  class Validator {
    // Login/Register user
    static userValidation() {
      // Errors
      $.validator.setDefaults({
        errorClass: "text-danger",
        highlight: function (element) {
          $(element).closest(".form-group").addClass("text-ternary");
        },
        unhighlight: function (element) {
          $(element).closest(".form-group").removeClass("text-ternary");
        },
        errorPlacement: function (error, element) {
          if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent());
          } else {
            error.insertAfter(element);
          }
        },
      });
      // Password Check
      $.validator.addMethod(
        "passCheck",
        (val, el) => {
          return val.length > 7 && RegExp.regPass.test(val) && val.length <= 25;
        },
        "Password must contain at least one uppercase, number and symbol (length 8-25)"
      );
      // Email Check
      $.validator.addMethod(
        "emailCheck",
        (val, el) => {
          return RegExp.regEmail.test(val);
        },
        "Password must contain at least one uppercase, number and symbol (length 8-25)"
      );
      // Phone Check
      $.validator.addMethod(
        "phoneCheck",
        (val, el) => {
          return RegExp.regPhone.test(val);
        },
        "Invalid phone number format. Must only contain numbers and/or (+, -, /))"
      );
      // Address Check
      $.validator.addMethod(
        "addrCheck",
        (val, el) => {
          return RegExp.regAddress.test(val);
        },
        "Invalid address format. eg. Mean Street No.44"
      );
      // Name Check
      $.validator.addMethod(
        "nameCheck",
        (val, el) => {
          return RegExp.regName.test(val);
        },
        ""
      );
      // Subject Check
      $.validator.addMethod("subjectCheck", (val, el) => {
        return RegExp.regSubject.test(val);
      });

      // LOGIN VALIDATION
      $("#loginForm").validate({
        rules: {
          inEmail: {
            required: true,
            emailCheck: true,
          },
          inPass: {
            required: true,
            passCheck: true,
          },
        },
        messages: {
          inEmail: {
            required: "Please enter a valid email",
            email: "Email is not valid",
          },
        },
        // SUBMIT HANDLER
        submitHandler: function (form) {
          Ajax.callback(
            form.action,
            form.method,
            $(form).serialize(),
            (data) => {
              console.log(data);
              Callback.redirect();
              localStorage.setItem("user", data.user);
              if (data.hasOwnProperty("cart")) {
                Storage.createOrderFromCart(data.cart);
              }
            },
            (err) => {
              console.log(err);
              const msg = err.responseJSON;
              const status = `Oops...${err.status} error occured`;
              // Helper.defaultModal("error-login", status, msg).show();
              Helper.errModal(status, msg).show();
            }
          );
        },
        // END SUBMIT HANDLER
      });
      // END LOGIN VALIDATION

      // REGISTER VALIDATION
      $("#registerForm").validate({
        rules: {
          inEmail: {
            required: true,
            emailCheck: true,
          },
          inFname: {
            required: true,
            nameCheck: true,
            // errorPlacement: (err, el) => {
            //   if (err) {
            //     document.querySelector("#inFnameErr").innerHTML =
            //       "First name must be between 2-15 letters";
            //   }
            // },
          },
          inLname: {
            required: true,
            nameCheck: true,
            // errorPlacement: (err, el) => {
            //   document.querySelector("#inLnameErr").innerHTML =
            //     "Last name must be between 2-15 letters";
            // },
          },
          inPass: {
            required: true,
            passCheck: true,
          },
          inPassConf: {
            required: true,
            passCheck: true,
            equalTo: "#inPass",
          },
          inPhone: {
            required: true,
            phoneCheck: true,
          },
          inAddr: {
            required: true,
            addrCheck: true,
          },
        },
        // SUBMIT HANDLER
        submitHandler: function (form) {
          Ajax.callback(
            form.action,
            form.method,
            $(form).serialize(),
            (data) => {
              console.log(data);
              localStorage.setItem("user", data.user);
              Callback.redirect();
            },
            (err) => {
              console.log("I go in here");
              // const msg = err.responseJSON["msg"];
              // const status = `Oops...${err.status} error occured`;
              // console.log(status);
              // Helper.defaultModal("error-register", status, msg).show();
              console.log(err);
              const msg = err.responseJSON;
              const status = `Oops...${err.status} error occured`;
              // Helper.defaultModal("error-login", status, msg).show();
              Helper.errModal(status, msg).show();
            }
          );
        },
        // END SUBMIT HANDLER
      });
      // END REGISTER VALIDATION
      // SEND MESSAGE
      $("#messageForm").validate({
        rules: {
          inFname: {
            required: true,
            nameCheck: true,
          },
          inLname: {
            required: true,
            nameCheck: true,
          },
          inEmail: {
            required: true,
            emailCheck: true,
          },
          inSubject: {
            required: true,
            subjectCheck: true,
            minlength: 2,
          },
          taMessage: {
            required: true,
            minlength: 2,
          },
        },
        messages: {
          inFname: {
            required: "First name is required",
            nameCheck: "First name must be between 2-15 characters",
          },
          inLname: {
            required: "Last name is required",
            nameCheck: "Last name must be between 2-15 characters",
          },
          inEmail: {
            required: "Email is required",
            emailCheck: "Must enter a valid email",
          },
          inSubject: {
            required: "Message subject is required",
            subjectCheck:
              "Subject name must only contain alphanumeric characters",
            minlength: "Message subject must contain at least 2 characters",
          },
          taMessage: {
            required: "Message body is required",
            minlength: "Message body must contain at least 2 characters",
          },
        },
        // SUBMIT HANDLER
        submitHandler: function (form) {
          Ajax.callback(
            form.action,
            form.method,
            $(form).serialize(),
            (data) => {
              Helper.defaultModal(
                "contact",
                "Message sent",
                "You have successfully sent a message"
              ).show();
            },
            (err) => {
              console.log("I go in here");
              console.log(err);
              const msg = err.responseJSON;
              const status = `Oops...${err.status} error occured`;
              // Helper.defaultModal("error-login", status, msg).show();
              Helper.errModal(status, msg).show();
            }
          );
        },
        // END SUBMIT HANDLER
      });
      // END SEND MESSAGE
    }
    //////////////////////////
    // Insert/Edit product
    static submitProduct() {
      // Errors
      $.validator.setDefaults({
        errorClass: "text-danger",
        highlight: function (element) {
          $(element).closest(".form-group").addClass("text-ternary");
        },
        unhighlight: function (element) {
          $(element).closest(".form-group").removeClass("text-ternary");
        },
        errorPlacement: function (error, element) {
          if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent().parent());
          } else {
            error.insertAfter(element);
          }
        },
      });

      // Name Check
      $.validator.addMethod(
        "nameCheck",
        (val, el) => {
          return RegExp.regGloveName.test(val);
        },
        "Invalid glove name"
      );
      // Price Check
      $.validator.addMethod(
        "priceCheck",
        (val, el) => {
          return RegExp.regPrice.test(val);
        },
        "Invalid price format eg. 59.99"
      );
      // // Checkbox Check
      // $.validator.addMethod(
      //   "cb",
      //   (val, el) => {
      //     return RegExp.regPrice.test(val);
      //   },
      //   "Invalid price format eg. 59.99"
      // );
      const mode = new URLSearchParams(window.location.search).get("mode");
      console.log(mode);
      if (mode == "insert") {
        // GLOVE VALIDATION INSERT
        $("#glovesModeFormInsert").validate({
          rules: {
            inName: {
              required: true,
              nameCheck: true,
            },
            inPrice: {
              required: true,
              priceCheck: true,
            },
            taDesc: {
              required: true,
            },
            ddlCat: {
              required: true,
            },
            ddlBrand: {
              required: true,
            },
            ddlColor: {
              required: true,
            },
            "cbSize[]": {
              required: true,
            },
          },
          messages: {
            inName: {
              required: "Glove name cannot be left empty",
            },
            inPrice: {
              required: "Price cannot be left empty eg. 59.99",
            },
            taDesc: {
              required: "Glove must have some description",
            },
            ddlBrand: {
              required: "Brand must be checked",
            },
            ddlCat: {
              required: "Category must be checked",
            },
            ddlColor: {
              required: "Color must be checked",
            },
            cbSize: {
              required: "At least one size must be checked",
            },
            // fileImg: {
            //   required: "Image must be selected",
            //   // accept: "Only image type jpg/png/jpeg are allowed",
            //   filesize: "Image must be less than 3MB",
            // },
          },
          // SUBMIT HANDLER
          submitHandler: function (form) {
            const data = new FormData(form);
            $.ajax({
              url: form.action,
              method: form.method,
              data: data,
              // Must be set to false
              contentType: false,
              cache: false,
              // Must not be set
              // dataType: "json",
              processData: false,
              success: (data) => {
                console.log(data);
                console.log(data.msg);
                Helper.defaultModal(
                  "glove-insert",
                  "Glove inserted successfully",
                  data.msg
                ).show();
                const mode = new URLSearchParams(window.location.search).get(
                  "mode"
                );
                if (mode == "insert") {
                  document.querySelector("#glovesModeFormInsert").reset();
                  document.querySelector("#cbSizes").innerHTML = "";
                }
              },
              error: (err) => {
                console.log(err);
                // const msg = err.responseJSON["msg"];
                // console.log(msg);
                // const status = `Oops...${err.status} error occured`;
                // Helper.defaultModal("error-glove-insert", status, msg).show();
                console.log(err);
                const msg = err.responseJSON;
                const status = `Oops...${err.status} error occured`;
                // Helper.defaultModal("error-login", status, msg).show();
                Helper.errModal(status, msg).show();
              },
            });
          },
          // END SUBMIT HANDLER
        });
        // END GLOVE VALIDATION INSERT
      } else {
        console.log("I go in here");
        // GLOVE VALIDATION EDIT
        $("#glovesModeFormEdit").validate({
          rules: {
            inName: {
              required: true,
              nameCheck: true,
            },
            inPrice: {
              required: true,
              priceCheck: true,
            },
            taDesc: {
              required: true,
            },
            ddlCat: {
              required: true,
            },
            ddlBrand: {
              required: true,
            },
            ddlColor: {
              required: true,
            },
            "cbSize[]": {
              required: true,
            },
            inHidden: {
              required: true,
            },
          },
          messages: {
            inName: {
              required: "Glove name cannot be left empty",
            },
            inPrice: {
              required: "Price cannot be left empty eg. 59.99",
            },
            taDesc: {
              required: "Glove must have some description",
            },
            ddlBrand: {
              required: "Brand must be checked",
            },
            ddlCat: {
              required: "Category must be checked",
            },
            ddlColor: {
              required: "Color must be checked",
            },
            cbSize: {
              required: "At least one size must be checked",
            },
            // fileImg: {
            //   // required: "Image must be selected",
            //   // accept: "Only image type jpg/png/jpeg are allowed",
            //   filesize: "Image must be less than 3MB"
            // },
          },
          // SUBMIT HANDLER
          submitHandler: function (form) {
            const data = new FormData(form);
            $.ajax({
              url: form.action,
              method: form.method,
              data: data,
              // Must be set to false
              contentType: false,
              // Must not be set
              // dataType: "json",
              processData: false,
              success: (data) => {
                console.log(data);
                console.log(data.msg);
                const newID = data.id;
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set("id", newID);
                window.location.search = urlParams;
                Helper.defaultModal(
                  "glove-update",
                  "Glove updated successfully",
                  data.msg
                ).show();
                if (data.hasOwnProperty("img")) {
                  document.querySelector(
                    "#editThumbImg"
                  ).src = `assets/img/gloves/thumbnail/${data.img}`;
                }
              },
              error: (err) => {
                console.log(err);
                // const msg = err.responseJSON["msg"];
                // console.log(msg);
                // const status = `Oops...${err.status} error occured`;
                // Helper.defaultModal("error-login", status, msg).show();
                console.log(err);
                const msg = err.responseJSON;
                const status = `Oops...${err.status} error occured`;
                // Helper.defaultModal("error-login", status, msg).show();
                Helper.errModal(status, msg).show();
              },
            });
          },
          // END SUBMIT HANDLER
        });
        // END GLOVE VALIDATION EDIT
      }
    }

    // Send Message
    static sendMessage() {}
  }

  // Listeners
  class Listeners {
    // Load types of sizes for specific glove (based on category)
    static loadSizesForCategory() {
      // Selected category determines what type of sizes will be available
      // eg. boxing -> oz;  karate/mma -> s/m/l/xl
      // Category dropdown
      const ddlCat = document.querySelector("#ddlCat");
      ddlCat.addEventListener("change", () => {
        const cat = Number(ddlCat.value);
        if (cat) {
          Ajax.callback(
            "models/users/admin/gloves/Category.php",
            "POST",
            {
              cat: cat,
            },
            (data) => {
              Display.sizeCheckboxes(data.data);
            },
            (err) => {
              // const msg = err.responseJSON["msg"];
              // const status = `Oops...${err.status} error occured`;
              // Helper.defaultModal("error-login", status, msg).show();
              console.log(err);
              const msg = err.responseJSON;
              const status = `Oops...${err.status} error occured`;
              // Helper.defaultModal("error-login", status, msg).show();
              Helper.errModal(status, msg).show();
            }
          );
        } else {
          document.querySelector("#cbSizes").innerHTML =
            "Category must be select first";
        }
      });
    }

    // Load all gloves that are not removed (date_removed)
    static loadGlovesTable(page = 1) {
      console.log("Does it matter");
      const data = {
        type: "gloves",
        categories: document.querySelector("#categories").value,
        brands: document.querySelector("#brands").value,
        colors: document.querySelector("#colors").value,
        sort: document.querySelector("#sort").value,
        range: document.querySelector("#range").value,
        perPage: document.querySelector("#perPage").value,
        page: page,
        search: document.querySelector("#search").value,
      };
      console.log(data);
      // Ajax
      Ajax.callback(
        "models/Filter.php",
        // "models/users/admin/gloves/ActiveGloves.php",
        "POST",
        data,
        (data) => {
          const arrGlovesObj = data[0].gloves;
          const pages = data[0].pages;
          const total = data[0].total;
          console.log(arrGlovesObj);
          console.log(pages);
          console.log(total);
          Display.allGlovesTable(arrGlovesObj, total);
          Display.pagesNumber(pages, page, "admin-table");
        },
        (err) => {
          // const msg = err.responseJSON["msg"];
          // const status = `Oops...${err.status} error occured`;
          // Helper.defaultModal("error-glove-table-admin", status, msg).show();
          console.log(err);
          const msg = err.responseJSON;
          const status = `Oops...${err.status} error occured`;
          // Helper.defaultModal("error-login", status, msg).show();
          Helper.errModal(status, msg).show();
        }
      );
    }

    // Remove glove from table
    static removeGloveIdTable(id) {
      Ajax.callback(
        "models/users/admin/gloves/Remove.php",
        "POST",
        {
          id: id,
        },
        (data) => {
          console.log(data);
          Listeners.loadGlovesTable();
        },
        (err) => {
          console.log(err);
          const msg = err.responseJSON;
          const status = `Oops...${err.status} error occured`;
          // Helper.defaultModal("error-login", status, msg).show();
          Helper.errModal(status, msg).show();
        }
      );
    }

    // SHOP - Attach Event Listeners to all filter/sort inputs
    static shopFilterEvents() {
      const checks = ["categories", "brands", "colors"];
      checks.forEach((check, i) => {
        const ch = document.querySelectorAll(`input[name='${check}']`);
        ch.forEach((c) => {
          c.addEventListener("change", () => {
            this.loadGlovesShop();
          });
        });
      });
      const change = ["range", "sort", "perPage"];
      change.forEach((c) => {
        document.querySelector(`#${c}`).addEventListener("change", () => {
          this.loadGlovesShop();
        });
      });
      document.querySelector("#search").addEventListener("keyup", () => {
        this.loadGlovesShop();
      });
    }

    // Load orders table (admin-orders)
    static loadOrdersTable(page = 1) {
      const id = new URLSearchParams(window.location.search).get("id");
      console.log(id);
      // Filtering data
      const data = {
        type: "orders",
        sort: document.querySelector("#sort").value,
        perPage: document.querySelector("#perPage").value,
        page: page,
        id: id ? id : null,
      };
      if (document.querySelector("#search")) {
        data.search = document.querySelector("#search").value;
      }
      Ajax.callback(
        "models/Filter.php",
        "POST",
        data,
        (data) => {
          console.log(data);
          const arrOrdersObj = data[0].orders;
          const pages = data[0].pages;
          const total = data[0].total;
          // console.log(arrGlovesObj);
          // console.log(pages);
          // console.log(total);
          Display.allOrdersTable(arrOrdersObj, total, id ? id : null);
          Display.pagesNumber(
            pages,
            page,
            id ? "admin-table-orders-id" : "admin-table-orders"
          );
        },
        (err) => {
          // const msg = err.responseJSON["msg"];
          // const status = `Oops...${err.status} error occured`;
          // Helper.defaultModal("error-glove-table-admin", status, msg).show();
          console.log(err);
          const msg = err.responseJSON;
          const status = `Oops...${err.status} error occured`;
          // Helper.defaultModal("error-login", status, msg).show();
          Helper.errModal(status, msg).show();
        }
      );
    }

    // Load users table (admin-users)
    static loadUsersTable(page = 1) {
      // Filtering data
      const data = {
        type: "users",
        sort: document.querySelector("#sort").value,
        perPage: document.querySelector("#perPage").value,
        page: page,
        search: document.querySelector("#search").value,
      };
      Ajax.callback(
        "models/Filter.php",
        "POST",
        data,
        (data) => {
          console.log(data);
          const arrUsersObj = data[0].users;
          const pages = data[0].pages;
          const total = data[0].total;
          // console.log(arrGlovesObj);
          // console.log(pages);
          // console.log(total);
          Display.allUsersTable(arrUsersObj, total);
          Display.pagesNumber(pages, page, "admin-table-users");
        },
        (err) => {
          // const msg = err.responseJSON["msg"];
          // const status = `Oops...${err.status} error occured`;
          // Helper.defaultModal("error-glove-table-admin", status, msg).show();
          console.log(err);
          const msg = err.responseJSON;
          const status = `Oops...${err.status} error occured`;
          // Helper.defaultModal("error-login", status, msg).show();
          Helper.errModal(status, msg).show();
        }
      );
    }

    // Load messages table (admin-messages)
    static loadMessagesTable(page = 1) {
      // Filtering data
      const data = {
        type: "messages",
        sort: document.querySelector("#sort").value,
        perPage: document.querySelector("#perPage").value,
        page: page,
        search: document.querySelector("#search").value,
      };
      console.log(data);
      Ajax.callback(
        "models/Filter.php",
        "POST",
        data,
        (data) => {
          console.log(data);
          const arrMessagesObj = data[0].messages;
          const pages = data[0].pages;
          const total = data[0].total;
          // console.log(arrGlovesObj);
          // console.log(pages);
          // console.log(total);
          Display.allMessagesTable(arrMessagesObj, total);
          Display.pagesNumber(pages, page, "admin-messages");
        },
        (err) => {
          // const msg = err.responseJSON["msg"];
          // const status = `Oops...${err.status} error occured`;
          // Helper.defaultModal("error-glove-table-admin", status, msg).show();
          console.log(err);
          const msg = err.responseJSON;
          const status = `Oops...${err.status} error occured`;
          // Helper.defaultModal("error-login", status, msg).show();
          Helper.errModal(status, msg).show();
        }
      );
    }

    // ADMIN-GLOVES - Attach Event Listeners to all filter/sort inputs
    static adminGlovesTableFilterEvents() {
      const change = [
        "categories",
        "brands",
        "colors",
        "perPage",
        "sort",
        "range",
      ];
      change.forEach((c) => {
        document.querySelector(`#${c}`).addEventListener("change", () => {
          this.loadGlovesTable();
        });
      });
      document.querySelector("#search").addEventListener("keyup", () => {
        this.loadGlovesTable();
      });
    }

    static loadDefaultFilterEvents($type) {
      console.log("DEFAULT FILTER EVENTS");
      console.log($type);
      const change = ["perPage", "sort"];
      change.forEach((c) => {
        document.querySelector(`#${c}`).addEventListener("change", () => {
          console.log($type);
          switch ($type) {
            case "admin-orders":
              this.loadOrdersTable();
              break;
            case "admin-users":
              this.loadUsersTable();
              break;
            case "admin-user":
              this.loadOrdersTable();
              break;
            case "admin-messages":
              console.log("IN HERE");
              this.loadMessagesTable();
              break;
          }
        });
      });
      if (document.querySelector("#search")) {
        document.querySelector("#search").addEventListener("keyup", () => {
          switch ($type) {
            case "admin-orders":
              this.loadOrdersTable();
              break;
            case "admin-users":
              this.loadUsersTable();
              break;
            case "admin-messages":
              this.loadMessagesTable();
              break;
          }
        });
      }
    }

    // Load, in Shop, all gloves that are active
    static loadGlovesShop(page = 1) {
      const data = {
        type: "gloves",
        categories: Helper.getCheckboxes("categories"),
        brands: Helper.getCheckboxes("brands"),
        colors: Helper.getCheckboxes("colors"),
        sort: document.querySelector("#sort").value,
        range: document.querySelector("#range").value,
        perPage: document.querySelector("#perPage").value,
        page: page,
        search: document.querySelector("#search").value,
      };

      Ajax.callback(
        "models/Filter.php",
        "POST",
        data,
        (data) => {
          const arrGlovesObj = data[0].gloves;
          const pages = data[0].pages;
          const total = data[0].total;
          Display.glovesShop(arrGlovesObj, pages, page, total);
        },
        (err) => {
          console.log(err);
          const msg = err.responseJSON;
          const status = `Oops...${err.status} error occured`;
          // Helper.defaultModal("error-login", status, msg).show();
          Helper.errModal(status, msg).show();
        }
      );
    }

    // Insert selected glove into cart
    static insertIntoCart() {
      console.log("READY TO LOAD SINGLE GLOVE");
      document.querySelector("#btnSubmit").addEventListener("click", (e) => {
        e.preventDefault();
        // Get the data and call the function only when the Add to cart button is clicked

        // Get the Order Line object and pass it to Order in localStorage
        const data = Data.returnOrderLine();
        console.log(data);

        Helper.defaultModal(
          "add-glove-to-cart",
          "Glove added",
          `Succesfully added ${data.inGloveName} to cart`
        ).show();
        Storage.addOrderLine(data);
        Helper.cartItemsNumber();

        // Initialize cart
      });
    }

    // Add event listeners to Quantity input as well as remove button
    static cartEvents() {
      const remove = document.querySelectorAll(".remove-link");
      remove.forEach((r, i) => {
        r.addEventListener("click", (e) => {
          e.preventDefault();
          const userID = +r.dataset.userid;
          const sizeID = +r.dataset.sizeid;
          const gloveID = +r.dataset.gloveid;

          console.log(userID, sizeID, gloveID);
          Storage.removeOrderLine(userID, gloveID, sizeID);
        });
      });
      const quantity = document.querySelectorAll(".quantity");
      quantity.forEach((q, i) => {
        q.addEventListener("change", (e) => {
          e.stopPropagation();
          e.preventDefault();
          const val = +q.value;
          const userID = +q.dataset.userid;
          const sizeID = +q.dataset.sizeid;
          const gloveID = +q.dataset.gloveid;

          // Cannot be less than 1
          if (val > 0) {
            const order = Storage.returnOrder();
            order.forEach((o) => {
              if (
                +o.inUserID === userID &&
                +o.raSize === sizeID &&
                +o.inGloveID === gloveID
              ) {
                // Found the current order line
                o.inQuantity = val;
                Storage.addOrderLine(o, "replace");
                Helper.cartItemsNumber();
              } else {
                console.log("Not found");
              }
            });
            Display.glovesCart();
          } else {
            // Value is less than 1
          }
        });
      });
    }

    // Add event listener to purchase button in Cart
    static purchaseCart() {
      const purchase = document.querySelector("#purchase");
      purchase.addEventListener("click", (e) => {
        e.preventDefault();
        const user = Helper.isCustomer();
        if (user) {
          // Is customer
          const isOrder = Helper.isOrder();
          if (isOrder) {
            // Can make purchase
            console.log("I in");
            Helper.defaultModal(
              "customer-purchase",
              "Order",
              "Successfully purchased",
              "Close"
            ).show();
            document
              .querySelector("#defaultModal")
              .addEventListener("hidden.bs.modal", () => {
                const data = Data.returnOrderDetail();
                Ajax.isFormData(
                  "models/users/customer/Purchase.php",
                  "POST",
                  data,
                  (data) => {
                    console.log(data);
                    const div = document.querySelector("#purchased");
                    div.innerHTML = `
                      <span class="material-icons align-middle me-2">
                        check
                      </span>Successfully purchased`;
                    div.classList.add("py-2");
                    div.classList.add("px-2");
                    Storage.removeOrder();
                    Helper.cartItemsNumber();
                    Display.glovesCart();
                  },
                  (err) => {
                    console.log("I go into error");
                    // console.log(err);
                    const msg = err.responseJSON;
                    const status = `Oops...${err.status} error occured`;
                    // Helper.defaultModal("error-login", status, msg).show();
                    Helper.errModal(status, msg).show();
                  }
                );
              });
          } else {
            // No items in cart, can't make purchase
            Helper.defaultModal(
              "admin-purchase",
              "Order failed",
              "Cannot make purchase, no items selected"
            ).show();
          }
        } else {
          // Not customer
          Helper.defaultModal(
            "admin-purchase",
            "Order failed",
            "Cannot make purchase, you are not a customer"
          ).show();
        }
      });
    }

    // Download order detail as Excel
    static downloadOrderDetailExcel() {
      const excel = document.querySelector("#download-order-detail-excel");
      console.log("Here's excel");
      console.log(excel);
      excel.addEventListener("click", (e) => {
        e.preventDefault();
        const orderID = excel.dataset.orderid;
        Ajax.callback(
          "models/users/admin/orders/Excel.php",
          "POST",
          { orderID: orderID },
          (data) => {
            console.log(data);
            console.log("we in ere");
            // const arrMessagesObj = data[0].messages;
            // const pages = data[0].pages;
            // const total = data[0].total;
            // console.log(arrGlovesObj);
            // console.log(pages);
            // console.log(total);
            // Display.allMessagesTable(arrMessagesObj, total);
            // Display.pagesNumber(pages, page, "admin-messages");
          },
          (err) => {
            // const msg = err.responseJSON["msg"];
            // const status = `Oops...${err.status} error occured`;
            // Helper.defaultModal("error-glove-table-admin", status, msg).show();
            console.log(err);
            const msg = err.responseJSON;
            const status = `Oops...${err.status} error occured`;
            // Helper.defaultModal("error-login", status, msg).show();
            Helper.errModal(status, msg).show();
          }
        );
      });
    }

    // // Unlock user's account
    // static unlockUser() {

    // }
  }

  // Display AJAX
  class Display {
    // Checkboxes for size
    static sizeCheckboxes(sizes) {
      let content = ``;
      sizes = Object.values(sizes);
      const div = document.querySelector("#cbSizes");

      sizes.forEach((s, i) => {
        content += `
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="cbSize[]" value="${
            s.id
          }" id="cbSize-${s.id}">
          <label class="form-check-label" for="flexCheckDefault">
            ${s.size}
            ${s.measure == "OZ" ? s.measure : ""}
          </label>
        </div>
        `;
      });
      div.innerHTML = content;
    }

    // Display Gloves Table
    static allGlovesTable(gloves, total) {
      let content = ``;
      console.log(gloves);
      document.querySelector("#gloveCount").innerHTML = total;
      gloves = Object.values(gloves);
      console.log(gloves);
      gloves.forEach((g, i) => {
        content += `
          <tr>
            <td class="align-middle">${i + 1}</td>
            <td class="align-middle"><img class='img-fluid' src="assets/img/gloves/thumbnail/${
              g.t_img
            }" alt="${g.name}"></td>
            <td class="align-middle">${g.name}</td>
            <td class="align-middle">${g.cat}</td>
            <td class="align-middle">${g.brand}</td>
            <td class="align-middle">
              <div class="d-flex align-items-center">
                <span class="palette border align-bottom" style="background-color:${
                  g.color
                }!important;"></span>
                <span class="capitalize ms-2 lead align-bottom">${
                  g.color
                }</span>
              </div>
            </td>
            <td class="align-middle">${g.sizes}</td>
            <td class="align-middle">${g.measure}</td>
            <td class="align-middle">${g.date_added}</td>
            <td class="align-middle">
              <a href="index.php?page=admin-gloves&mode=edit&id=${
                g.id
              }" class="text-secondary">
                <span class="material-icons">
                  edit
                </span>
              </a>
            </td>
            <td class="align-middle">
              <a href="#" data-id="${g.id}" class="text-ternary remove-link">
                <span class="material-icons">
                  delete
                </span>
              </a>
            </td>
          </tr>
        `;
      });
      document.querySelector("#glovesTableBody").innerHTML = content;
      // Listeners added after the content is renderred
      const removeLinks = document.querySelectorAll(".remove-link");
      removeLinks.forEach((l) => {
        l.addEventListener("click", (e) => {
          // Prevents the EVENT not the link itself
          e.preventDefault();
          Listeners.removeGloveIdTable(Number(l.dataset.id));
        });
      });
    }

    // Display Orders Tabel
    static allOrdersTable(orders, total, id) {
      console.log(orders);
      console.log(total);
      console.log("WE HERE");
      let content = ``;
      document.querySelector("#ordersCount").innerHTML = total;
      orders = Object.values(orders);
      orders.forEach((g, i) => {
        console.log(g);
        content += `
          <tr>
            <td class="align-middle">${i + 1}</td>
            <td class="align-middle"><a class="text-secondary" href="index.php?page=admin-${
              id ? "order&id=" + g.id_order_detail : "user&id=" + g.id_user
            }">${g.first_name} ${g.last_name}</a></td>
            <td class="align-middle">${g.count}</td>
            <td class="align-middle">$${g.total}</td>
            <td class="align-middle">${g.date}</td>
            <td class="align-middle">
              <a href="index.php?page=admin-order&id=${
                g.id_order_detail
              }" class="text-ternary more-details">
                <span class="material-icons">
                  receipt_long
                </span>
              </a>
            </td>
          </tr>
        `;
      });
      document.querySelector("#ordersTableBody").innerHTML = content;
      // document.querySelector("#ordersTableBody").innerHTML = content;
      // Listeners added after the content is renderred
    }

    // Display Users Table
    static allUsersTable(users, total) {
      console.log(users);
      let content = ``;
      document.querySelector("#usersCount").innerHTML = total;
      users = Object.values(users);
      users.forEach((g, i) => {
        console.log(g);
        content += `
          <tr>
            <td class="align-middle">${i + 1}</td>
            <td class="align-middle"><a class="text-secondary" href="index.php?page=admin-user&id=${
              g.id_user
            }">${g.first_name} ${g.last_name}</a></td>
            <td class="align-middle">${g.email_user}</td>
            <td class="align-middle">${+g.count ? g.count : "/"}</td>
            <td class="align-middle">${g.total ? "$" + g.total : "/"}</td>
            <td class="align-middle">
              <a href="index.php?page=admin-user&id=${
                g.id_user
              }" data-id="" class="text-ternary remove-link">
                <span class="material-icons">
                  person
                </span>
              </a>
            </td>
            <td>
              ${Helper.isLock(g.isLock, g.id_user)}
            </td>
          </tr>
        `;
      });
      document.querySelector("#usersTableBody").innerHTML = content;

      const unlock = document.querySelectorAll(".locked-account");
      unlock.forEach((u, i) => {
        u.addEventListener("click", (e) => {
          e.preventDefault();
          const userID = u.dataset.id;
          Ajax.callback(
            "models/users/admin/users/Unlock.php",
            "POST",
            { userID: userID },
            (data) => {
              document.querySelector(`#unlock-${userID}`).innerHTML = `
                <span class="material-icons">
                  check
                </span>
              `;
            },
            (err) => {
              // const msg = err.responseJSON["msg"];
              // const status = `Oops...${err.status} error occured`;
              // Helper.defaultModal("error-login", status, msg).show();
              console.log(err);
              const msg = err.responseJSON;
              const status = `Oops...${err.status} error occured`;
              // Helper.defaultModal("error-login", status, msg).show();
              Helper.errModal(status, msg).show();
            }
          );
        });
      });
      // Display.pagesNumber(pages, page, "shop");
    }

    // Display Messages Table
    static allMessagesTable(messages, total) {
      console.log(messages);
      let content = ``;
      document.querySelector("#messagesCount").innerHTML = total;
      messages = Object.values(messages);
      messages.forEach((g, i) => {
        console.log(g);
        content += `
          <tr>
            <td class="align-middle">${i + 1}</td>
            <td class="align-middle"><a class="text-secondary" href="index.php?page=admin-message&id=${
              g.id_msg
            }">${g.msg_first_name} ${g.msg_last_name}</a></td>
            <td class="align-middle">${g.msg_email}</td>
            <td class="align-middle">${g.msg_subject}</td>
            <td class="align-middle">${g.msg_date}</td>
            <td class="align-middle">
            <a href="index.php?page=admin-message&id=${
              g.id_msg
            }" class="text-ternary more-details">
              <span class="material-icons">
                message
              </span>
            </a>
          </td>
          </tr>
        `;
      });
      document.querySelector("#messagesTableBody").innerHTML = content;
    }

    // Display all available gloves as well as pages
    static glovesShop(arrGlovesObj, pages, page, total) {
      console.log(arrGlovesObj);
      document.querySelector("#gloveCount").innerHTML = total;
      arrGlovesObj = Object.values(arrGlovesObj);
      console.log(arrGlovesObj);
      let content = ``;
      const div = document.querySelector("#gloves");

      for (let i in arrGlovesObj) {
        content += `
              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 sol-12 mt-4">
              <figure class="card card-product-grid box-shadow">
                  <div class="img-wrap position-relative">
                      <img src="assets/img/gloves/normal/${
                        arrGlovesObj[i].n_img
                      }" class="img-fluid fit-cover">
                  </div>
                  <figcaption class="py-2 px-3 mt-3">
                      <div>
                          <div>
                              <p class="position-absolute top-0 start-0 bg-light box-shadow lead text-secondary py-2 mt-4 px-3">${
                                arrGlovesObj[i].brand
                              }</p>
                              <h5 class="d-block lead">
                                  <span class="material-icons align-bottom text-secondary">
                                      attach_money
                                  </span>
                                  <label>
                                      ${arrGlovesObj[i].price}
                                  </label>
                              </h5>
                          </div>
                          <div>
                              <h5 class="d-block lead">
                                  <span class="material-icons align-bottom text-secondary">
                                      sports_mma
                                  </span>
                                  <label>
                                      ${arrGlovesObj[i].name}
                                  </label>
                              </h5>
                          </div>
                          <div>
                              <h5 class="d-block lead">
                                  <span class="material-icons align-bottom text-secondary">
                                    straighten
                                  </span>
                                  <label>
                                      ${arrGlovesObj[i].sizes} ${
          arrGlovesObj[i].measure === "OZ"
            ? "(" + arrGlovesObj[i].measure + ")"
            : ""
        }
                                  </label>
                              </h5>
                          </div>
                          <div class="mt-3 d-flex align-items-center">
                              <span class="palette border align-bottom" style="background-color:${
                                arrGlovesObj[i].color
                              }!important;"></span>
                              <label class="capitalize ms-2 lead">${
                                arrGlovesObj[i].color
                              }</label>
                          </div>
                      </div>
                      <div class="my-3">
                          <a class="btn box-shadow-hover border mb-2" href="index.php?page=glove&id=${
                            arrGlovesObj[i].id
                          }" data-prodid="${arrGlovesObj[i].id}">View</a>
                      </div>
                  </figcaption>
              </figure>
          </div>
        `;
      }
      div.innerHTML = content;
      Display.pagesNumber(pages, page, "shop");
    }

    // Display number of pages (pagination)
    static pagesNumber(pages, page, type = "shop") {
      console.log(pages);
      let content = ``;
      for (let i = 1; i <= pages; i++) {
        content += `
          <li class="page-item"><a class="page-link ${
            page == i ? "active-page" : ""
          }" href="#" data-id="${i}">${i}</a></li>
        `;
      }
      // pages.forEach((p, i) => {
      //   content += `
      //     <li class="page-item"><a class="page-link ${page == (i+1) ? 'active' : '' }" href="#" dataid="${i+1}">1</a></li>
      //   `;
      // });
      document.querySelector("#pages").innerHTML = content;
      const links = document.querySelectorAll(".page-link");
      links.forEach((l) => {
        l.addEventListener("click", (e) => {
          e.preventDefault();
          const id = l.dataset.id;
          console.log(type);
          console.log(id);
          switch (type) {
            case "shop":
              Listeners.loadGlovesShop(id);
              break;
            case "admin-table":
              Listeners.loadGlovesTable(id);
              break;
            case "admin-table-orders":
              Listeners.loadOrdersTable(id);
              break;
            case "admin-table-orders-id":
              Listeners.loadOrdersTable(id);
              break;
            case "admin-table-users":
              Listeners.loadUsersTable(id);
              break;
            case "admin-messages":
              Listeners.loadMessagesTable(id);
              break;
          }
        });
      });
    }

    // Display, in cart, all gloves from localStorage
    static glovesCart() {
      const cart = document.querySelector("#cart");
      const order = Storage.returnOrder();
      console.log(order);
      let content = ``;
      let total = 0;
      if (order.length >= 0) {
        order.forEach((g, i) => {
          total +=
            Math.round(Number(g.inQuantity) * Number(g.inPrice) * 100) / 100;
          content += `
            <tr>
              <td class="align-middle">${i + 1}</td>
              <td class="align-middle"><img class='img-fluid' src="assets/img/gloves/thumbnail/${
                g.inImg
              }" alt="${g.inGloveName}"></td>
              <td class="align-middle">${g.inGloveName}</td>
              <td class="align-middle">${g.inCatName}</td>
              <td class="align-middle">${g.inBrandName}</td>
              <td class="align-middle">
                <div class="d-flex align-items-center">
                  <span class="palette border align-bottom" style="background-color:${
                    g.inColor
                  }!important;"></span>
                  <span class="capitalize ms-2 lead align-bottom">${
                    g.inColor
                  }</span>
                </div>
              </td>
              <td class="align-middle">${g.raSizeName}${
            g.inMeasure === "OZ" ? g.inMeasure : ""
          }</td>
              <td class="align-middle">${g.inPrice}</td>
              <td class="align-middle">
                  <input 
                  type="number" 
                  data-userid="${g.inUserID}" 
                  data-sizeid="${g.raSize}" 
                  data-gloveid="${g.inGloveID}" 
                  class="quantity" 
                  min="1" 
                  value="${g.inQuantity}"
              >
              </td>
              <td class="align-middle">$ 
                ${
                  Math.round(Number(g.inQuantity) * Number(g.inPrice) * 100) /
                  100
                }
              </td>
              <td class="align-middle">
                <a 
                  href="#" 
                  data-userid="${g.inUserID}" 
                  data-sizeid="${g.raSize}" 
                  data-gloveid="${g.inGloveID}" 
                  class="text-ternary remove-link"
                >
                  <span class="material-icons">
                    delete
                  </span>
                </a>
              </td>
            </tr>
          `;
        });
        // Append content to cart table
        cart.innerHTML = content;
        const prices = ["price", "totalPrice"];
        total = Math.round(total * 100) / 100;
        prices.forEach((p) => {
          document.querySelector(`#${p}`).innerHTML = total;
        });
        // console.log(Math.round(total * 100) / 100);
        Listeners.cartEvents();
      } else {
        // There are no items in cart
      }
    }
  }

  // IIFE
  (() => {
    // Classes
    // 1. Listeners -> make request to backend and get the data
    // 2. Display -> get data from Listeners and render content
    // 3. Validator -> handle client side validation
    Helper.cartItemsNumber();
    Helper.isLogout();
    const urlParams = new URLSearchParams(window.location.search).get("page");
    if (urlParams) {
      switch (urlParams) {
        // GENERAL
        case "shop":
          Listeners.shopFilterEvents();
          Listeners.loadGlovesShop();
          break;
        case "cart":
          console.log("CART CLIENT SIDE");
          Display.glovesCart();
          Listeners.purchaseCart();
          break;
        case "glove":
          console.log("GLOVE CLIENT SIDE");
          Helper.isCustomer() ? Listeners.insertIntoCart() : "";
          // Listeners.insertIntoCart();
          break;
        case "contact":
          console.log("WE IN CONTACT");
          Validator.userValidation();
          break;

        // AUTH
        case "login":
          Validator.userValidation();
          break;
        case "register":
          console.log("Register page");
          Validator.userValidation();
          break;

        // CUSTOMER

        // ADMIN
        case "products-insert":
          Validator.insertProduct();
          console.log("PRODUCTS-INSERT");
          break;

        // ADMIN GLOVES (DEFAULT, INSERT, UPDATE)
        case "admin-gloves":
          const mode = new URLSearchParams(window.location.search).get("mode");
          if (mode) {
            switch (mode) {
              case "insert":
                Listeners.loadSizesForCategory();
                Validator.submitProduct();
                break;
              case "edit":
                Listeners.loadSizesForCategory();
                Validator.submitProduct();
                console.log("Edit existing glove");
                break;
              default:
                console.log("DEFAULT GLOVES");
            }
          } else {
            console.log("TABLE GLOVES");
            Listeners.adminGlovesTableFilterEvents();
            Listeners.loadGlovesTable();
          }
          break;
        // END ADMIN GLOVES (DEFAULT, INSERT, UPDATE)

        case "admin-orders":
          console.log("ADMIN ORDERS");
          Listeners.loadDefaultFilterEvents("admin-orders");
          Listeners.loadOrdersTable();
          break;
        case "admin-users":
          console.log("ADMIN USERS");
          Listeners.loadDefaultFilterEvents("admin-users");
          Listeners.loadUsersTable();
          break;
        case "admin-order":
          console.log("ADMIN ORDER DETAIL");
          // Listeners.downloadOrderDetailExcel();
          break;
        case "admin-user":
          console.log("ADMIN USER DETAIL");
          Listeners.loadDefaultFilterEvents("admin-user");
          Listeners.loadOrdersTable();
          break;
        case "admin-messages":
          Listeners.loadDefaultFilterEvents("admin-messages");
          Listeners.loadMessagesTable();
          console.log("ADMIN MESSAGES");
          break;
        // DEFAULT
        default:
          console.log("INDEX PAGE");
      }
    } else {
      console.log("INDEX PAGE BUT ITS NOT SWITCH");
    }
  })();
});
