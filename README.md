
<p align="center">
    <a href="https://sim.pli.city">
        <img src="/Public/res/planet.png" width="188" title="Simplicity" alt="Simplicity">
    </a>
</p>

# Simplicity

Hello and welcome to Simplicity!<br>
	  
Simplicity is a light, simple, software on premise to build and own your buddy network.<br>
	   
Simplicity is released under BSD license, it is supplied AS-IS and we do not take any responsibility for its misusage.<br>
	   
First step, use the password box and salt fields to create all the hashes to insert in the config file. Remember to manually set there also the salt value.<br>

Rember that the buddy creation has some limitation:
- one group / department can have only eight buddies;
- one zone can have max two buddies.
	   
As you are going to run Simplicity in the PHP process context, using a limited web server or phpfpm user, you must follow some simple directives for an optimal first setup:<br>

<ol>
<li>Check the write permissions of your "data" folder in your web app Private path; and set its path in the config file.</li>
<li>Set the default Locale.</li>
</ol> 

By default Simplicity open up in Demo mode but the software is fully. Every user can now login with the password to checkin.<br>

<br>

### Public view:

![Simplicity in action #1](/Public/res/screenshot1.png)<br>

### Admin view:

![Simplicity in action #2](/Public/res/screenshot2.png)<br>

Feedback: <a href="mailto:posta@elettronica.lol" style="color:#e6d236;">posta@elettronica.lol</a>


