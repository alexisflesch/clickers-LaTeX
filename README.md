# Online poll system
Ever wanted LaTeX-compatible clickers system? This is a simple PHP/mySQL script that will help you do just that. Plus, it supports multiple users.

# How does it work once everything is set-up ?
You can use the admin page to upload a text file to your website. This file should contain environments "clickers" that look like this :

    \begin{clickers}
        Some question
        \begin{itemize}
            \item One answer
            \item Another answer
            \item $\LaTeX$ code if you want
        \end{itemize}
    \end{clickers}

The PHP script will automatically fetch all the clickers in your text file and let you select the one you want to work with. Feel free to take a look at the "screenshots" folder in which you will find :
- phone.png : a screenshot of what your students will see
- admin.png : a screenshot of the admin page where you can upload a text file/select a question
- timer.png : a screenshot of a countdown you can use with your students

# Licence
The timer is adapted from this code https://codepen.io/icebob/pen/JYoQZg that doesn't seem to come with any licence. It's thus released under the same licence - that is with no licence.
Everything else is released under the CC-BY-SA licence (https://creativecommons.org/licenses/by-sa/3.0/)

# Getting started
To get started, please fill in your SQL info in files :
- admin/php/main.php (line 17)
- vote/php/main.php (line 5)

Then modify this file according to your setup
- admin/.htaccess

Now, create a user named "root" in your .htpasswd (only user "root" will be able to add/delete users).

Once you are done, upload both folders (vote and admin) to your website and create two SQL tables. The first one should be named "clickers" and have two columns :
- name (Primary key) : char(16)
- voteId : char(16)

This table will contain the vote ids of your users. The second table is optional, you need to create it "by hand" for user root if you want him to have access to the poll system. Create a table named "clickers_root" with two columns :
- reponse (Primary key) : int(11)
- votes : int(11)

You're good to go ! Simply go to yoursite/admin and you will be able to create new users, start a poll, etc... Send your students to yoursite/vote to vote.
