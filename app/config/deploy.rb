set :application, "Sense2Party"
set :domain,      "web.piwi-web.com"
set :deploy_to,   "/home/s2p/domains/sense2party.nl/public_html"

set :user,        "s2p"
# set :password,    ""
set :use_sudo,    false

set :ssh_key,     "~/.ssh/id_rsa"
set :app_path,    "app"
set :web_path, 	  "web"
set :maintenance_basename, 	"maintenance"

# Dump assetic after deployment
set :dump_assetic_assets, true
set :assets_install, true
set :assets_symlinks, true

# Confirmations will not be requested from the command line.
set :interactive_mode, false

# SCM info
set :repository,  "git@github.com:piwi91/sense2party.git"
set :scm,         :git
set :deploy_via,  :copy
set :branch,      "master"

set :model_manager, "doctrine"

# Role info. I don't think this is particularly important for Capifony...
role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

# General config stuff
set :keep_releases,  3
set :shared_files,      ["app/config/parameters.yml"] # This stops us from having to recreate the parameters file on every deploy.
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor", web_path + "/media"]
set :permission_method, :acl
set :use_composer, true

# User details for the production server
ssh_options[:forward_agent] = true
ssh_options[:keys] = [ssh_key]

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

# Run migrations before warming the cache
before "symfony:cache:warmup", "symfony:doctrine:migrations:migrate"
