# AWS-Project
# Overview
This project demonstrates the deployment of a secure, scalable web application using AWS services. The application consists of an EC2 instance running a web server, an RDS database, an EFS for shared storage, and an S3 bucket integrated with CloudFront for video delivery. Additionally, an Auto Scaling Group (ASG) and Load Balancer (ALB) ensure high availability and fault tolerance.

## Architecture
- **VPC**: Custom VPC with 6 subnets
  - 2 Public Subnets (for ELB and Jump Server)
  - 2 Private Subnets (for Application Servers)
  - 2 Private Subnets (for Database Servers)
- **Security Groups**:
  - Jump Server (SSH)
  - RDS (MySQL)
  - ELB (HTTP, HTTPS)
  - EC2 Web Servers (HTTP, SSH)
  - EFS (NFS)
- **AWS Services Used**:
  - EC2 (for web and application servers)
  - RDS (MySQL database instance)
  - EFS (for shared storage across multiple EC2 instances)
  - S3 (for storing video content)
  - CloudFront (for content delivery)
  - Load Balancer (for distributing traffic)
  - Auto Scaling Group (for high availability)

## Steps to Deploy

### Step 1: Setup AWS Resources
1. **Create an S3 bucket** and upload sample video files.
2. **Create a CloudFront distribution** to serve the videos through edge locations.
3. **Create a VPC** with the required subnets and security groups.
4. **Create an RDS database** with a DB subnet group.
5. **Launch a Jump Server** in the public subnet and install MySQL client.

### Step 2: Configure EC2 Instance
1. **Launch an EC2 instance** in a private subnet.
2. **Install required packages**:
   ```bash
   sudo yum install -y httpd mysql
   sudo amazon-linux-extras install -y lamp-mariadb10.2-php7.2 php7.2
   service httpd start
   chkconfig httpd on
   ```
3. **Connect to the RDS database** and create necessary tables and users:
   ```sql
   CREATE DATABASE capstone;
   CREATE TABLE capstone.customers (
       id INT(11) NOT NULL AUTO_INCREMENT,
       name VARCHAR(50) NOT NULL,
       gender VARCHAR(50) NOT NULL,
       email VARCHAR(50) NOT NULL,
       phone VARCHAR(20) NOT NULL,
       PRIMARY KEY (id)
   );
   CREATE USER 'capstoneuser' IDENTIFIED BY 'Akash12345';
   GRANT ALL PRIVILEGES ON capstone.* TO capstoneuser;
   FLUSH PRIVILEGES;
   ```
4. **Mount EFS to `/var/www/html/`**:
   ```bash
   sudo mount -t nfs4 -o nfsvers=4.1,rsize=1048576,wsize=1048576,hard,timeo=600,retrans=2,noresvport fs-<EFS_ID>.efs.<REGION>.amazonaws.com:/ /var/www/html/
   ```
5. **Update `/etc/fstab` for permanent mount**.

### Step 3: Deploy Web Application
1. **Upload web content to S3** and copy it to `/var/www/html/`.
2. **Modify `submit.php` and `submit2.php`** to include database connection details.
3. **Update `video1.html` and `video2.html`** with CloudFront URLs.
4. **Test the application** by accessing `http://webserver-privateIP/`.

### Step 4: Enable HTTPS and Load Balancer
1. **Configure HTTP to HTTPS redirection**.
2. **Create an SSL certificate and hosted zone in Route 53**.
3. **Create an Application Load Balancer** and Target Group.
4. **Create a Launch Template and Auto Scaling Group**.
5. **Test Auto Scaling** by terminating instances and observing replacements.

### Step 5: Monitoring and Backup
1. **Create an Alarm** for EC2 termination.
2. **Configure Data Lifecycle Manager (DLM)** for daily backups.
3. **Verify data storage** in the RDS database:
   ```sql
   SELECT * FROM capstone.customers;
   ```

## Conclusion
This project showcases best practices for deploying a web application on AWS with high availability, scalability, and security. By integrating EC2, RDS, EFS, CloudFront, and S3, the application ensures optimal performance and reliability.

