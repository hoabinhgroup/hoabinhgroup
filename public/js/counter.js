var Visitors = new (function()
{
   // Check the jQuery exists if not then return the alert
   if(typeof jQuery == "undefined") return alert("jQuery is required for Ajax visitors module!");
   
   // Set the visitors status container html element
   this.container = $("#online-users");
   
   // Create the interval variable
   this.online_interval = null;
   
   // Declare the refresh method
   this.refresh = function()
   {
      console.log("Visitors.refresh() executed");
   
      jQuery.ajax({
         url: "default/online/visit",
         success: function(data, textStatus, jqXHR)
         {
	         console.log(data);
            var json = $.parseJSON(data);
            console.debug("Visitors.refresh: ajax request was successfull", json);
            
            if(json.success) // If the module was successfull
            {
               console.debug("Visitors.refresh: replace the visitors container content");
               Visitors.container.html( json.visits );
            }
         }
      });
   }
   
   console.debug("Visitors: set the interval to keep up to date the module");
   this.online_interval = setInterval(function()
   {
      Visitors.refresh();
   },
   5000);
   
   console.debug("Visitors: Refresh the visitors data");
   this.refresh();
   
   console.log("Visitors: Module JavaScript object created successfull");
   return this; // Return the $this object to the variable
});