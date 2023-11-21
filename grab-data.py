#!/usr/bin/python3


import os
import sys
import time
import json
import random
import requests
import argparse
from colored import fg, bg, attr


TOKENS_FILE = os.path.dirname(os.path.realpath(__file__))+'/.tokens'
REPOSITORIES_FILE = os.path.dirname(os.path.realpath(__file__))+'/repositories.txt'
REPOSITORIES_OUTPUT_FILE = os.path.dirname(os.path.realpath(__file__))+'/repositories.json'
GITHUB_API_URL = 'https://api.github.com/'


def getRandomToken( t_tokens ):
    time.sleep( 2 )
    token = random.choice( t_tokens )
    print(token)
    return token


def loadUsers():
    t_users = open(USERS_FILE,'r').read().strip().split("\n")
    return t_users


def loadRepositories():
    if args.repo:
        t_repositories = args.repo.split(',')
    else:
        t_repositories = open(REPOSITORIES_FILE,'r').read().strip().split("\n")
    return t_repositories


def loadTokens():
    t_tokens = []
    gh_env =  os.getenv('GITHUB_TOKEN')

    if args.token:
        t_tokens = args.token.split(',')
    else:
        if gh_env:
            t_tokens = gh_env.strip().split(',')
        else:
            if os.path.isfile(TOKENS_FILE):
                fp = open(TOKENS_FILE,'r')
                for line in fp:
                    r = re.search( '^([a-f0-9]{40}|ghp_[a-zA-Z0-9]{36})$', line )
                    if r:
                        t_tokens.append( r.group(1) )

    if not len(t_tokens):
        parser.error( 'auth token is missing' )

    return t_tokens


def getRepositoryStars( repo, start_page=1 ):
    run = True
    per_page = 100
    page = int(start_page)
    t_repo_stars = {}
    print(repo)

    while run:
        time.sleep( random.random() )
        url = GITHUB_API_URL + 'repos/'+repo+'/stargazers?per_page='+str(per_page)+'&page='+str(page)
        try:
            r = requests.get(url, headers={'Accept':'application/vnd.github.v3.star+json','Authorization':'token '+getRandomToken(t_tokens)})
        except Exception as e:
            sys.stdout.write("%s%s%s\n" % (fg('red'),e,attr(0)) )
            continue

        rj = r.json()
        # print(rj)
        print("%d %d" % (page,len(rj)))

        if 'documentation_url' in rj:
            run = False
            continue

        t_repo_stars[str(page)] = []
        for stars in rj:
            # print(stars)
            t_repo_stars[str(page)].append( stars['starred_at'] )

        if len(rj) < per_page:
            run = False

        page = page + 1

    print(t_repo_stars)
    return t_repo_stars




parser = argparse.ArgumentParser()
parser.add_argument( "-t","--token",help="your github token (required)" )
parser.add_argument( "-r","--repo",help="repository to check" )
parser.add_argument( "-1","--init",help="init stats and override the previous data of the concerned repo", action="store_true" )
parser.add_argument( "-2","--flush",help="init stats and override the previous data of all repos", action="store_true" )
parser.parse_args()
args = parser.parse_args()

if args.init:
    option_init = True
else:
    option_init = False

if args.flush:
    option_flush = True
else:
    option_flush = False


t_tokens = loadTokens()
# print(t_tokens)
t_repositories = loadRepositories()
# print(t_repositories)

t_repositories_stats = {}

if not option_flush:
    with open(REPOSITORIES_OUTPUT_FILE, 'r') as f:
        t_repositories_stats = json.load(f)
# print(t_repositories_stats)

for repo in t_repositories:
    repo = repo.replace('https://github.com/','')
    sys.stdout.write("%s[+]%s get repo data: %s\n" % (fg('green'),attr(0),repo) )

    if option_flush or option_init or not repo in t_repositories_stats:
    # if option_init or not repo in t_repositories_stats:
        start_page = 1
        t_repositories_stats[repo] = {}
    else:
        n_pages = list(t_repositories_stats[repo].keys())
        if len(n_pages) == 0:
            start_page = 1
        else:
            start_page = n_pages[len(n_pages)-1]

    # print(start_page)
    t_stats = getRepositoryStars( repo, int(start_page) )

    for page,stats in t_stats.items():
        t_repositories_stats[repo][str(page)] = stats

    # break

with open(REPOSITORIES_OUTPUT_FILE, 'w') as f:
    json.dump(t_repositories_stats, f)

