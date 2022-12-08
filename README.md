# Sportissimo test app

Installing local version of the project
==================================

### Pre-install ###
1. Install the [docker](https://docs.docker.com/get-docker/) into your local device.
2. Install the [docker-compose](https://docs.docker.com/compose/install/) into your local device.
3. Install the `Make` (Mac - https://formulae.brew.sh/formula/make. Linux - `sudo apt install build-essential`).

### Main install ###
1. Clone the repository into your local device: `git clone https://github.com/barmax/sportissimo test_app`.
2. Go to the project folder: `cd test_app`.
3. Build containers for the project: `make build-dev`.
4. Run the project containers: `make recreate-dev`.
5. Install project dependencies: `make install-dev`.
6. Create db-table: `make create-table`. 
7. To run web environment, run command `npm run dev`.
8. Go to the link [http://localhost:8081](http://localhost:8081).
9. Click the button `Seed the database`.
