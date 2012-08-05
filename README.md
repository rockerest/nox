Nox
===
What is Nox?
------------
Nox is a library for small web applications to build on.  It provides a simple, object-oriented foundation to get the repetitive parts of setting up a website out of the way as fast as possible.

What isn't Nox?
---------------
A framework.  Nox doesn't provide routing (yet), a MVC structure (yet), or any built-in validation (yet).
These are things that mature *frameworks* do, and Nox isn't trying to be a framework (yet).

How can I use Nox?
------------------
Nox is about ease of use.  It's designed in a way that the developer found intuitive, and it's constantly evolving.
There isn't a lot to do from a code perspective to get Nox running.  What you do need to do is outlined here:
  1. Set up the database (You can use the MySQL Workbench file in __project_files_)
  2. Modify the nox.conf JSON file in _components\system\_ to contain the correct database login information, root path, and other configuration information
  3. Download Swiftmailer and unpack it to the _vendors_ folder as "Swiftmailer".  Nox *might* run without Swiftmailer, but it's untested and not recommended.
  4. Download the Fugue Icon Pack and unpack the various sizes to the _images\icons\[SIZE]_ folders.  Nox will run without the Fugue Icon Pack, but it will look ugly.

Otherwise, you can use it however you like!

What if I have issues?
----------------------
If you can fix it, submit a pull request.  If you can't, leave a comment.  If you don't want to do either of those things, I can't help you right now!
This library is under regular, heavy development and changes are made frequently and often completely break backwards compatibility.
A good idea of when this could be considered "alpha" software would be when I create a tag from the master branch (that won't happen for a while).

You said "you can use it however you like."  Does that mean I own it?
---------------------------------------------------------------------
I don't know.  I don't understand copyright law, and I don't really understand license law, either.
For now, I retain full ownership and you can do whatever you want with it right now, but it would be really great if - if you improve or modify the code - you also provided that change to others, free of charge.
This "license" isn't permanent, and I reserve the right to revoke any licenses granted at any time.
