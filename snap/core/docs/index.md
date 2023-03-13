# SNAP Core Package #

The SNAP Core package provides the foundational SNAP object. This includes a couple helper functions and a SNAP facade:

## Facade ##
The `SNAP` facade is a wrapper around the `\Snap\Core\SNAPManager` class that allows you to attach and detach objects to the global SNAP object. The following methods are included: 
* `SNAP::attach($key, $obj = null)`: Attaches an object to the SNAPManager object.
* `SNAP::dettach($key)`: Detaches an object to the SNAPManager object.
* `SNAP::has($key)`: Returns a boolean value based on if an key for an object exists or not on the SNAPManager object.
  
## Helpers ##
* `snap()`: A convenience function that returns the `\Snap\Core\SNAPManager` object.
* `snap_path()`: Returns the server path to the snap packages directory.
