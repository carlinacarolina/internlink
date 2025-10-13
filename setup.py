import os
import shutil
import subprocess
import json

project_dir = os.path.dirname(os.path.abspath(__file__))


def _which(*candidates: str) -> str | None:
    for name in candidates:
        path = shutil.which(name)
        if path:
            return path
    return None


def _run(args: list[str], cwd: str | None = None) -> None:
    try:
        subprocess.run(args, check=True, cwd=cwd or project_dir)
    except subprocess.CalledProcessError as e:
        print(f"Command failed ({e.returncode}): {' '.join(args)}")
        raise


def condition_input(message: str) -> bool:
    while True:
        response = input(message)
        response = response.lower()

        if response in ["y", "yes"]:
            return True

        elif response in ["n", "no"]:
            return False

        else:
            print("Invalid response. Please enter y or n.")

def vendor_check():
    try:
        vendor_dir = os.path.join(project_dir, "vendor")

        composer_cmd = _which("composer", "composer.bat")
        php_cmd = _which("php", "php.exe")

        local_phar = os.path.join(project_dir, "composer.phar")
        use_phar = not composer_cmd and os.path.isfile(local_phar) and php_cmd

        if not composer_cmd and not use_phar:
            print(
                "Composer not found.\n"
                "Hint: composer.phar exists locally; ensure PHP is installed to use it,\n"
                "or install Composer globally.\n"
                "Windows: winget install -e --id Composer.Composer\n"
                "Linux:   sudo apt-get install composer (or your distro)\n"
            )
            return False

        def composer_install():
            if use_phar:
                _run([php_cmd, local_phar, "install"], cwd=project_dir)
            else:
                _run([composer_cmd, "install"], cwd=project_dir)

        if os.path.isdir(vendor_dir):
            if condition_input("Vendor already exists. Overwrite it? (y/n): "):
                shutil.rmtree(vendor_dir, ignore_errors=True)
                composer_install()
            else:
                print("Vendor not overwritten.")
        else:
            composer_install()

        return True

    except Exception as e:
        print(f"Error (composer): {e}")
        return False


def node_modules_check():
    try:
        nm_dir = os.path.join(project_dir, "node_modules")

        npm_cmd = _which("npm", "npm.cmd")
        if not npm_cmd:
            print(
                "npm (Node.js) not found.\n"
                "Windows: winget install -e --id OpenJS.NodeJS.LTS\n"
                "Linux:   sudo apt-get install nodejs npm (or nvm)\n"
            )
            return False

        def npm(*args: str) -> None:
            _run([npm_cmd, *args], cwd=project_dir)

        lockfile = os.path.join(project_dir, "package-lock.json")
        pkg_json_path = os.path.join(project_dir, "package.json")
        has_lock = os.path.isfile(lockfile)

        def has_build_script() -> bool:
            if not os.path.isfile(pkg_json_path):
                return False
            try:
                with open(pkg_json_path, encoding="utf-8") as f:
                    pkg = json.load(f)
                scripts = pkg.get("scripts", {})
                return isinstance(scripts, dict) and "build" in scripts
            except Exception:
                return False

        if os.path.isdir(nm_dir):
            if condition_input("node_modules already exists. Overwrite it? (y/n): "):
                shutil.rmtree(nm_dir, ignore_errors=True)
                npm("ci" if has_lock else "install")
                if has_build_script():
                    npm("run", "build")
            else:
                print("Node modules not overwritten.")
        else:
            npm("ci" if has_lock else "install")
            if has_build_script():
                npm("run", "build")

        return True

    except Exception as e:
        print(f"Error (npm): {e}")
        return False



def env_check():
    try:
        env_path = os.path.join(project_dir, ".env")
        example_path = os.path.join(project_dir, ".env.example")

        if not os.path.isfile(example_path):
            print(".env.example not found.")
            return False

        if os.path.isfile(env_path):
            overwrite = condition_input("Env file already exists. Do you want to overwrite it? (y/n): ")
            if overwrite:
                shutil.copy(example_path, env_path)

            else:
                print("Env not overwritten.")
                return True

        else:
            shutil.copy(example_path, env_path)

        with open(env_path, "r", encoding="utf-8") as f:
            lines = f.readlines()

        targets = ["DB_HOST=", "DB_PORT=", "DB_DATABASE=", "DB_USERNAME=", "DB_PASSWORD="]

        for i, line in enumerate(lines):
            for key in targets:
                if line.strip().startswith(key):
                    default_val = line.split("=", 1)[1].strip() if "=" in line else ""
                    new_val = input(f"Enter {key} (default: {default_val}): ").strip()
                    if new_val == "":
                        new_val = default_val

                    lines[i] = f"{key}{new_val}\n"
                    break

        with open(env_path, "w", encoding="utf-8") as f:
            f.writelines(lines)

        print(".env successfully created or updated.")
        return True

    except Exception as e:
        print(f"Error (.env): {e}")
        return False


def migrate_check():
    try:
        php_cmd = _which("php", "php.exe")
        if not php_cmd:
            print("PHP not found in PATH.")
            return False

        artisan = os.path.join(project_dir, "artisan")
        if not os.path.isfile(artisan):
            print("artisan file not found.")
            return False

        if condition_input("Migrate the database now? (y/n): "):
            _run([php_cmd, "artisan", "migrate"], cwd=project_dir)
            if condition_input("Seed the database? (y/n): "):
                _run([php_cmd, "artisan", "db:seed"], cwd=project_dir)
            else:
                print("Database not seeded.")
        else:
            print("Database not migrated.")

        return True

    except Exception as e:
        print(f"Error (migrate): {e}")
        return False


def key_check():
    try:
        env_path = os.path.join(project_dir, ".env")
        if os.path.exists(env_path):
            php_cmd = _which("php", "php.exe")
            if not php_cmd:
                print("PHP not found in PATH.")
                return False

            if condition_input("Generate new APP_KEY? (y/n): "):
                _run([php_cmd, "artisan", "key:generate"], cwd=project_dir)
            else:
                print("Key not overwritten.")

        return True

    except Exception as e:
        print(f"Error (key): {e}")
        return False


def main():
    if not vendor_check():
        return

    if not node_modules_check():
        return

    if not env_check():
        return

    if not migrate_check():
        return

    if not key_check():
        return


if __name__ == "__main__":
    main()
