Usage Example in composer.json

    "extra": {
        "koala-framework-subproject": {
            "decoupledchild": {
                "install": "npm install",
                "build:dev": "npm run build:dev",
                "build:prod": "npm run build:prod"
            }
        }
    }
