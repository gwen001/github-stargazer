<h1 align="center">github-stargazer</h1>

<h4 align="center">View stars evolution of GitHub repositories.</h4>

<p align="center">
    <img src="https://img.shields.io/badge/python-v3-blue" alt="python badge">
    <img src="https://img.shields.io/badge/php-blue" alt="php badge">
    <img src="https://img.shields.io/badge/license-MIT-green" alt="MIT license badge">
    <a href="https://twitter.com/intent/tweet?text=https%3a%2f%2fgithub.com%2fgwen001%2fgithub-stargazer%2f" target="_blank"><img src="https://img.shields.io/twitter/url?style=social&url=https%3A%2F%2Fgithub.com%2Fgwen001%2Fgithub-stargazer" alt="twitter badge"></a>
</p>

<!-- <p align="center">
    <img src="https://img.shields.io/github/stars/gwen001/github-stargazer?style=social" alt="github stars badge">
    <img src="https://img.shields.io/github/watchers/gwen001/github-stargazer?style=social" alt="github watchers badge">
    <img src="https://img.shields.io/github/forks/gwen001/github-stargazer?style=social" alt="github forks badge">
</p> -->

---

## Description

This PHP/Python tool grabs repositories data to create a graphical representation of the evolution of stars.

## Install

```
git clone https://github.com/gwen001/github-stargazer
```

**The repository must be cloned in a web directory.**

## Usage

- Create an environment variable `GITHUB_TOKEN` that contains your GitHub token.
- Edit the file `repositories.txt` to add the repositories your want to track.
- Remove the current file `repositories.json`.
- Run the script `grab.py` to grab the data whenever you want.
- Visit `index.php` to see the graph.

---

<img src="https://raw.githubusercontent.com/gwen001/github-stargazer/main/overall-progress.png">
<img src="https://raw.githubusercontent.com/gwen001/github-stargazer/main/daily-progress.png">

---

Feel free to [open an issue](/../../issues/) if you have any problem with the script.  

