namespace :mage do
  desc <<-DESC
    Clear the Magento Cache
  DESC
  task :cc, :roles => [:web, :app] do
    run "cd #{current_path}#{app_webroot} && rm -rf var/cache/* && php -r \"require_once('app/Mage.php'); Mage::app()->cleanCache();\""
  end
  end

namespace :db do
  desc <<-DESC
    Dump important tables of Database
  DESC
  task :dump, :roles => [:web, :app] do
    run "cd #{current_path}#{app_webroot}/shell && php database.php dump"
    puts "for downloading Dump: scp root@#{hostname}:#{deploy_to}/current/mysqldump/dump.sql ."
  end
  desc <<-DESC
    Export Database
  DESC
  task :export, :roles => [:web, :app] do
    run "cd #{current_path}#{app_webroot}/shell && php database.php export"
  end
  desc <<-DESC
    Overwrite database with dumped database
  DESC
  task :overwrite, :roles => [:web, :app] do
    run "cd #{current_path}#{app_webroot}/shell && php database.php overwrite"
  end
end

namespace :deploy do
  desc "Recreate symlink"
  task :resymlink, :roles => :app do
    run "rm -f #{current_path} && cd #{deploy_to} && ln -s releases/#{release_name} current"
  end
end

after "deploy:create_symlink", "deploy:resymlink"