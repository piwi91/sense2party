set :application, "Cordstrap Sales Tool"
set :domain,      "##domain##"
set :deploy_to,   "##deploy_to##"

set :user,        "##deploy_user##"
set :use_sudo,    false

set :ssh_key,     "##ssh_key##"
set :app_path,    "app"
set :web_path, 	  "web"
set :maintenance_basename, 	"maintenance"

# Dump assetic after deployment
set :dump_assetic_assets, true

# Confirmations will not be requested from the command line.
set :interactive_mode, false

# SCM info
set :repository,  "##git_repository##"
set :scm,         :git
set :deploy_via,  :remote_cache
set :branch,      "##git_branch##"

set :model_manager, "doctrine"

# Role info. I don't think this is particularly important for Capifony...
role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

# General config stuff
set :keep_releases,  ##keep_releases##
set :shared_files,      ["app/config/parameters.yml"] # This stops us from having to recreate the parameters file on every deploy.
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]
set :permission_method, :acl
set :use_composer, true

# User details for the production server
ssh_options[:forward_agent] = true
ssh_options[:keys] = [ssh_key]

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

# Run migrations before warming the cache
set :application, "Cordstrap Sales Tool"
set :domain,      "##domain##"
set :deploy_to,   "##deploy_to##"

set :user,        "##deploy_user##"
set :use_sudo,    false

set :ssh_key,     "##ssh_key##"
set :app_path,    "app"
set :web_path, 	  "web"
set :maintenance_basename, 	"maintenance"

# Dump assetic after deployment
set :dump_assetic_assets, true

# Confirmations will not be requested from the command line.
set :interactive_mode, false

# SCM info
set :repository,  "##git_repository##"
set :scm,         :git
set :deploy_via,  :remote_cache
set :branch,      "##git_branch##"

set :model_manager, "doctrine"

# Role info. I don't think this is particularly important for Capifony...
role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

# General config stuff
set :keep_releases,  ##keep_releases##
set :shared_files,      ["app/config/parameters.yml"] # This stops us from having to recreate the parameters file on every deploy.
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]
set :permission_method, :acl
set :use_composer, true

# User details for the production server
ssh_options[:forward_agent] = true
ssh_options[:keys] = [ssh_key]

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

# Run migrations before warming the cache
# before "symfony:cache:warmup", "symfony:doctrine:migrations:migrate"